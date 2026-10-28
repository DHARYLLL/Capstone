# PROJECT RED AI (DARIV PLATFORM) — ARCHITECTURE & PROJECT NOTES

> **Purpose**: This file serves as the definitive reference manual for this codebase. Any agent, developer, or assistant should consult this document first for architectural understanding, data flow, routes, and database models before scanning the code.

---

## 1. Executive Summary

**Project RED AI** is a multi-tenant, **Retrieval-Augmented Generation (RAG) Customer Support Platform** built with **Laravel 12**. 

It provides businesses with:
1. **AI Chatbot powered by Google Gemini** capable of answering inquiries strictly based on indexed business knowledge chunks.
2. **Knowledge Ingestion Pipeline** supporting manual chunks, CSV, and PDF ingestion with a pre-production **Staging & Approval** review step.
3. **Human-in-the-Loop (HITL) Escalation**: Automatic and customer-triggered handoff from bot to human operators, with real-time live chat desks for Administrators and Lead Operators/Staff.
4. **Knowledge Deficit Tracking**: Low ratings and unanswerable queries are captured as deficits to allow administrators to plug knowledge gaps.
5. **Multi-Tenant Quota & Isolation**: Enforces tenant-level storage quotas, vector chunk quotas, and API key authorization.

---

## 2. Technology Stack & Key Dependencies

| Component | Technology | Notes |
|:---|:---|:---|
| **Backend Framework** | Laravel 12.x / PHP ^8.2 | MVC architecture, Queue workers, Blade templating |
| **Database** | PostgreSQL (Supabase) | Configured with Supabase transaction pooler (`5432`) |
| **LLM & Embeddings** | `google-gemini-php/laravel` | Utilizes Gemini models (e.g. `gemini-2.5-flash` / text-embedding) |
| **PDF Extraction** | `smalot/pdfparser` (^2.12) | Parses text from uploaded PDFs before chunking |
| **CSV Processing** | `league/csv` (^9.28) | Ingests CSVs into contextual chunks |
| **Email Service** | `resend/resend-laravel` (^1.6) | Transactional emails (e.g. password reset) |
| **Frontend Styling** | Tailwind CSS & DaisyUI | Admin/Staff UI dashboards, modals, and widget UI |
| **Testing** | Pest PHP (^3.8) | Unit and Feature testing suite |

---

## 3. Database Schema & Data Models

### Relationship Hierarchy
```
Company (Tenant)
 ├── Users (Admins, Lead Operators / Staff)
 ├── BusinessUnits (Branches / Divisions / Sub-units)
 │    ├── BusinessKnowledges (Active Chunks with Embeddings)
 │    ├── StagedKnowledgeDocuments (Pending PDF/CSV Uploads)
 │    └── ChatSessions (Conversations)
 │         ├── ChatMessages (Turns with Intent classification)
 │         │    └── ChatFeedback (Ratings & Comments)
 │         │         └── KnowledgeDeficit (Identified knowledge gaps)
 │         └── AssignedUser (Operator assigned via User model)
 └── ActivityLogs (Audit trail)
```

### Key Models (`app/Models/`)

1. **`Company`** (`companies`)
   - `name`, `contact_email`, `contact_phone`, `contact_address`
   - `api_key`: Secret tenant key prefixed with `pk_live_` (auto-generated on creation)
   - `storage_quota_bytes`, `vector_chunk_quota`: Tenant rate/storage limits

2. **`BusinessUnit`** (`business_units`)
   - Represents a specific store branch, department, or business entity under a company
   - `company_id`, `name`, `description`

3. **`User`** (`users`)
   - `company_id`, `name`, `email`, `password`, `role`, `last_seen_at`
   - Roles:
     - `User::ROLE_ADMIN` (`'Administrator'`)
     - `User::ROLE_OPERATOR` (`'Lead Operator'`)
     - `User::ROLE_BUSINESS_OWNER` (`'business_owner'`)
   - Soft deletes enabled (`SoftDeletes`)
   - Helper: `currentStatus()` returns `Active`, `Online`, `Offline`, or `Terminated` based on heartbeat and active chat sessions.

4. **`BusinessKnowledge`** (`business_knowledges`)
   - The production RAG knowledge store
   - `company_id`, `business_unit_id`, `content` (text chunk), `embedding` (vector data)

5. **`StagedKnowledgeDocument`** (`staged_knowledge_documents`)
   - Pre-approval queue for uploaded documents (PDF/CSV)
   - `business_unit_id`, `branch`, `original_name`, `mime_type`, `file_size`, `stored_path`, `edited_content`, `ingestion_mode`, `status` (`staged`, `approved`, `discarded`)

6. **`ChatSession`** (`chat_sessions`)
   - Conversation session tracking
   - `company_id`, `business_unit_id`, `user_identifier` (guest UUID or auth ID), `status`, `assigned_user_id`, `handed_off_at`
   - Statuses:
     - `bot_active`: Bot handles queries
     - `waiting` / `pending` / `queued`: Needs human agent claim
     - `human_active` / `handed_off`: Connected to human operator

7. **`ChatMessage`** (`chat_messages`)
   - `chat_session_id`, `sender_type` (`customer`, `bot`, `agent`), `message_text`, `intent`

8. **`ChatFeedback`** (`chat_feedbacks`)
   - `chat_message_id`, `rating` (1–5 / thumb), `comments`

9. **`KnowledgeDeficit`** (`knowledge_deficits`)
   - Gaps flagged automatically from negative feedback or missed questions
   - `chat_feedback_id`, `company_id`, `business_unit_id`, `chat_session_id`, `chat_message_id`, `customer_query`, `missing_context_details`, `status`, `flagged_at`

10. **`ActivityLog`** (`activity_logs`)
    - Audit log for changes
    - `user_id`, `event` (`Indexed`, `Staged`, `Deleted`, `Failed`, etc.), `status`, `file_name`, `division`, `type`

---

## 4. Key Services, Jobs & Background Processing

- **`App\Services\TextChunker`**: Splits raw document text into semantically sized chunks suitable for vector storage and LLM context windows.
- **`App\Services\CsvIngestionService`**: Parses CSV records, formats headers/rows into readable factual statements, and prepares chunks.
- **`App\Services\OrganizationQuotaService`**: Asserts whether an organization has remaining chunk quota or storage capacity before ingestion.
- **`App\Jobs\ProcessPdfIngestion`**: Background queued job parsing uploaded PDF files using `smalot/pdfparser`, extracting clean text, chunking, and staging.
- **`App\Jobs\ProcessChatFeedbackJob`**: Evaluates chat feedback, determines if negative ratings represent missing factual knowledge, and generates `KnowledgeDeficit` entries.
- **`App\Notifications\ResetPasswordNotification`**: Delivers branded password reset emails using Resend.

---

## 5. System Workflows & Core Logic

### A. Customer Chat & RAG Workflow (`ChatbotController@ask`)
1. **Request**: Customer sends `prompt` to `/chat/{businessUnit}/ask` (with optional `user_identifier` / `session_id`).
2. **Session State Check**:
   - If session is `human_active`, `waiting`, or `pending`, the message is appended and passed directly to the human queue without triggering Gemini.
3. **Handoff Detection**:
   - The query is analyzed via `containsHandoffTrigger()`. If the user asks for a live human, status changes to `waiting`/`pending`, `handed_off_at` is set, and the user is told an operator is connecting.
4. **Knowledge Retrieval**:
   - If handled by bot, relevant `BusinessKnowledge` content for the `business_unit_id` is retrieved.
5. **Prompt Assembly**:
   - Strict system instructions are injected: AI must strictly use provided facts, mirror language/dialect, handle arithmetic if needed, and gracefully admit lack of knowledge.
   - Recent chat history turns (last 8 messages) are prepended.
6. **Gemini Invocation**:
   - Calls `Gemini::generativeModel('gemini-2.5-flash')->generateContent(...)`.
   - Bot reply is persisted as `ChatMessage` (`sender_type = 'bot'`) and returned to the client as Markdown.

### B. Knowledge Base Ingestion & Staging Workflow
1. Admin uploads a PDF or CSV in `/admin/knowledge-base/upload`.
2. Document is stored in storage and a `StagedKnowledgeDocument` record is created with `status = 'staged'`.
3. Background job (`ProcessPdfIngestion`) extracts and chunks text.
4. Admin opens `/admin/knowledge-base/staged`:
   - Admin can review, modify text inline, and hit **Approve** (`/admin/knowledge-base/staged/{id}/approve`) or **Discard**.
5. Upon approval:
   - Final chunks are inserted into `business_knowledges` with vector embeddings.
   - An `ActivityLog` entry (`Indexed`, `Done`) is created.

### C. Live Operator Desk (HITL) Workflow
1. Customer initiates handoff or low confidence triggers it.
2. The session appears in the pending queue in **Admin Chat** (`/admin/chat`) and **Staff Chat** (`/staff/chat`).
3. An operator clicks **Claim Session** (`POST /admin/chat/{session}/claim` or `/staff/chats/{session}/claim`).
   - Sets `assigned_user_id = auth->id()`, `status = 'human_active'`.
4. Operator sends replies directly via `POST .../reply`.
5. Customer widget periodically polls `GET /get-messages/{sessionId}` to fetch new incoming operator messages.
6. When resolved, the operator clicks **Resolve** (`POST .../resolve`), closing out the session.

### D. Admin Dashboard & Real-Time Metrics Pipeline (`DashboardController`)
The Admin Overview (`/admin`) is completely data-driven and strictly scoped to the authenticated admin's `company_id`:
1. **Dynamic Tenant Identity**: Resolves `auth()->user()->company` and its `businessUnits` to display real company titles and branch subtitles.
2. **Core Performance KPIs (30-day window with historical delta comparison)**:
   - **Answered Queries**: Counts customer messages that received a corresponding bot reply.
   - **Unanswered Queries**: `Total customer queries - Answered queries`.
   - **Human-Routed Queries**: Sessions with `handed_off_at` or in escalation statuses (`human_active`, `handoff_requested`, `waiting`, `pending`).
   - **Knowledge Files**: Total active `BusinessKnowledge` records scoped to the tenant.
3. **Query Breakdown Distribution**: Calculates real percentages for AI Automation vs. Human Escalation vs. Unanswered, with clean zero-state handling for new tenants.
4. **Live System Health Checks**:
   - **AI Assistant Engine**: Actively checks `config('gemini.api_key')` (`Online · RAG Active` vs `Offline · Missing API Key`).
   - **Knowledge Ingestion**: Queries `StagedKnowledgeDocument` for pending uploads (`X Awaiting Review` vs `Synchronized`).
   - **Avg Response Time**: Real computed latency between recent customer queries and subsequent bot replies.
   - **Active Staff Online**: Real count of tenant staff with `last_seen_at >= now() - 5 minutes`.
5. **Recent Activity Timeline**: Scoped to company activity logs with relative timestamps (`diffForHumans()`).

---

## 6. Route & API Architecture

### Public & Customer Chat
- `GET /` & `/{slug}`: Redirects to login or dashboard.
- `GET /chat/widget`: Embeddable iframe widget (`?business={id_or_name}`).
- `GET /chat/{businessUnit}`: Standalone full-screen chat page.
- `POST /chat/{businessUnit}/ask`: Core AI chatbot endpoint.
- `GET /get-messages/{sessionId}`: Polling endpoint for live operator responses.
- `POST /chat/feedback`: Submit rating and user comments.
- `POST /api/chatbot/cancel-handoff`: Customer cancels human escalation.

### Authentication (`AuthController`)
- `GET /login`, `POST /login` (`login.attempt`)
- `GET /register`, `POST /register` (`register.store`)
- `GET /logout`
- `GET /forgot-password`, `POST /forgot-password` (`password.email`)
- `GET /reset-password/{token}`, `POST /reset-password` (`password.update`)

### Admin Console (`/admin/*` — Requires Role: `Administrator`)
- `GET /admin`: Analytics & dashboard overview.
- `GET /admin/knowledge-base`: Browse, search, manually add, edit, or delete active chunks.
- `POST /admin/knowledge-base/chunks`: Store single chunk with embedding.
- `PATCH /admin/knowledge-base/chunks/{id}`: Update chunk content.
- `DELETE /admin/knowledge-base/chunks/{id}`: Delete chunk.
- `POST /admin/knowledge-base/upload`: Ingest documents.
- `GET /admin/knowledge-base/staged`: Staging queue interface.
- `POST /admin/knowledge-base/staged/{id}/approve`: Approve staged chunk into active KB.
- `DELETE /admin/knowledge-base/staged/{id}`: Discard staged document.
- `GET /admin/chat`: Admin live operator desk.
- `GET /admin/chat/sessions`: JSON stream of live sessions & waiting count.
- `POST /admin/chat/{session}/claim`: Claim session.
- `POST /admin/chat/{session}/reply`: Send message as live agent.
- `POST /admin/chat/{session}/resolve`: Mark session as resolved.
- `GET /admin/analytics`: Quantitative charts & metrics.
- `GET /admin/staff`: List staff, view live statuses, add/edit/terminate/restore staff.
- `GET /admin/logs`: Audit trail filterable by status (`indexed`, `staged`, `failed`, `deleted`).
- `GET /admin/settings`: Admin profile and password settings.

### Staff Console (`/staff/*` — Requires Auth / `Lead Operator`)
- `GET /staff/chat`: Operator chat desk.
- `GET /staff/chats`: List waiting and assigned chat sessions.
- `POST /staff/chats/{session}/claim`: Claim incoming ticket.
- `POST /staff/chats/{session}/messages`: Post operator reply.
- `POST /staff/chats/{session}/resolve`: Resolve ticket.
- `GET /staff/settings`: Operator profile settings.

---

## 7. Default Accounts & Testing Credentials

Seeded via `Database\Seeders\AdminUserSeeder`:

| Role | Email | Password | Scope |
|:---|:---|:---|:---|
| **Administrator** | `admin@dariv.com` | `password` | Full Tenant Admin (`company_id = 1`) |
| **Lead Operator** | `mae.s@dariv.com` | `password` | Staff / Operator Console (`company_id = 1`) |

---

## 8. Getting Started Guide (For New Developers Cloning This Repo)

If someone is cloning this repository for the first time, follow these exact step-by-step instructions:

### Step 1: System Prerequisites
Ensure the local machine has:
- **PHP 8.2+** with extensions enabled: `pdo_pgsql` (or `pdo_sqlite`), `mbstring`, `openssl`, `curl`, `fileinfo`
- **Composer** (latest)
- **Node.js** (v18+ or v20+) and **npm**
- Access to **PostgreSQL** (e.g. Supabase, Docker, or local PostgreSQL). *Alternatively, SQLite can be used for quick local offline development.*

---

### Step 2: Clone and Install Dependencies

```bash
# 1. Clone repository
git clone <repo-url>
cd capstone1

# 2. Install PHP packages
composer install

# 3. Install JavaScript dependencies
npm install
```

---

### Step 3: Configure Environment Variables

```bash
# Copy example env file
cp .env.example .env

# Generate application encryption key
php artisan key:generate
```

Open `.env` and fill in the required keys:

1. **Database Configuration** (Default is PostgreSQL / Supabase):
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=aws-0-ap-northeast-1.pooler.supabase.com  # or 127.0.0.1
   DB_PORT=5432
   DB_DATABASE=postgres
   DB_USERNAME=postgres.<your-project-id>
   DB_PASSWORD=your_database_password
   ```
   *(If testing locally with SQLite: set `DB_CONNECTION=sqlite`, comment out the other DB lines, and run `touch database/database.sqlite`)*

2. **Google Gemini API Key** (Required for chatbot and RAG answers):
   ```env
   GEMINI_API_KEY=your_gemini_api_key_here
   ```
   Get an API key from [Google AI Studio](https://aistudio.google.com/).

3. **Mail Service** (For password reset emails):
   ```env
   MAIL_MAILER=resend
   RESEND_API_KEY=your_resend_api_key_here
   ```
   *(For offline local testing without Resend, you can change `MAIL_MAILER=log` so reset links log to `storage/logs/laravel.log`)*

---

### Step 4: Run Migrations and Seeders

Run the database migrations and seed the initial tenant company and default user accounts:

```bash
php artisan migrate --seed
```

This creates the tables and seeds default test users:
- **Admin**: `admin@dariv.com` (password: `password`)
- **Operator**: `mae.s@dariv.com` (password: `password`)

---

### Step 5: Link Storage & Run Dev Servers

```bash
# Ensure storage symlink exists
php artisan storage:link

# Start all processes simultaneously (Laravel server + Queue listener + Vite)
composer run dev
```

> **Note**: `composer run dev` runs three processes concurrently:
> 1. `php artisan serve` (Runs the web server at `http://127.0.0.1:8000`)
> 2. `php artisan queue:listen --tries=1` (Processes PDF background ingestion and feedback tasks)
> 3. `npm run dev` (Vite asset compiling and hot-reload)

---

### Step 6: Verify Everything Works

1. Visit **`http://127.0.0.1:8000/login`** in your browser.
2. Sign in with:
   - **Email**: `admin@dariv.com`
   - **Password**: `password`
3. Verify access to `/admin`: Check Knowledge Base, Live Chat Desk, Analytics, and Staff management.
4. Test the Chat Widget at: **`http://127.0.0.1:8000/chat/widget?business=1`**.

---

## 9. Common Developer Commands

```bash
# Run full development stack (Laravel Server + Queue Worker + Vite)
composer run dev

# Run individual components
php artisan serve
php artisan queue:listen --tries=1
npm run dev

# Run database migrations with seeders
php artisan migrate --seed

# Run Pest test suite
composer test
# or
php artisan test
```

---

## 9. Important Conventions & Rules

1. **Company Scope**: Never query or update `BusinessKnowledge`, `ChatSession`, `BusinessUnit`, or `User` without scoping to the current user's or request's `company_id`.
2. **Quotas**: Always invoke `OrganizationQuotaService` before inserting new chunks or uploading files to enforce limits.
3. **Audit Logging**: Any administrative change to the knowledge base (manual chunk creation, staging approval, chunk update, or deletion) must create a record using `ActivityLog::record(...)`.
4. **Soft Deletes**: Staff accounts are terminated via soft deletion (`SoftDeletes`) and can be restored via `/admin/staff/{id}/restore`.
5. **No Direct Model Hallucination**: AI system prompts must enforce strict grounding on indexed facts and avoid making up business policies or prices not present in `BusinessKnowledge`.

