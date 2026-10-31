<?php

namespace App\Enums;

enum StaffRole: string
{
    case ADMINISTRATOR = 'Administrator';
    case BUSINESS_OWNER = 'business_owner';
    case LEAD_OPERATOR = 'Lead Operator';

    /**
     * Normalize values stored by older versions of the application.
     */
    public static function normalize(?string $role): ?string
    {
        return match (strtolower(trim((string) $role))) {
            'admin', 'administrator' => self::ADMINISTRATOR->value,
            'business_owner', 'owner' => self::BUSINESS_OWNER->value,
            'agent', 'staff', 'lead operator', 'operator' => self::LEAD_OPERATOR->value,
            default => $role !== null ? trim($role) : null,
        };
    }

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(static fn (self $role): string => $role->value, self::cases());
    }
}
