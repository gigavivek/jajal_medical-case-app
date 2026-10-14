<?php

namespace App\Models;

use Database\Factories\MedicalCaseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalCase extends Model
{
    /** @use HasFactory<MedicalCaseFactory> */
    use HasFactory;

    public const STATUS_DRAFT = 'Draft';
    public const STATUS_PLANNING = 'Planning';
    public const STATUS_IN_REVIEW = 'In Review';
    public const STATUS_APPROVED = 'Approved';
    public const STATUS_COMPLETED = 'Completed';
    public const STATUS_CANCELLED = 'Cancelled';

    public const PRIORITY_LOW = 'Low';
    public const PRIORITY_MEDIUM = 'Medium';
    public const PRIORITY_HIGH = 'High';

    public const STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_PLANNING,
        self::STATUS_IN_REVIEW,
        self::STATUS_APPROVED,
        self::STATUS_COMPLETED,
        self::STATUS_CANCELLED,
    ];

    public const PRIORITIES = [
        self::PRIORITY_LOW,
        self::PRIORITY_MEDIUM,
        self::PRIORITY_HIGH,
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'medical_cases';

    /**
     * Mass assignable attributes.
     *
     * @var list<string>
     */
    protected $fillable = [
        'case_number',
        'patient_reference',
        'surgeon_name',
        'implant_type',
        'status',
        'priority',
        'surgery_date',
        'created_by',
    ];

    /**
     * Attributes casting.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'surgery_date' => 'date',
        ];
    }

    /**
     * User who created this medical case.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
