<?php

namespace Database\Factories;

use App\Models\MedicalCase;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MedicalCase>
 */
class MedicalCaseFactory extends Factory
{
    protected $model = MedicalCase::class;

    protected static int $caseSequence = 1;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $implantTypes = [
            'Titanium 3D-Printed Cranial Mesh',
            'PEEK Interbody Spinal Cage (L4-L5)',
            'Ceramic Total Hip Joint Stem',
            'Cobalt-Chrome Femoral Knee Implant',
            'Bioresorbable Vascular Scaffold',
            'Trabecular Metal Acetabular Cup',
            'Custom Patient-Specific Mandibular Plate',
            'Bipolar Radial Head Prosthesis',
            'Dual-Mobility Acetabular System',
            'Anterior Cervical Discectomy Plate',
            'Reverse Shoulder Glenosphere Implant',
            'Custom Pediatric Orthopedic Osteotomy Guide',
        ];

        $surgeons = [
            'Dr. Rajesh Jajal, MD',
            'Dr. Meera Iyer, MS (Ortho)',
            'Dr. Vikram Malhotra, MCh (Neuro)',
            'Dr. Sarah Jenkins, FRCS',
            'Dr. Amit Kulkarni, DNB',
            'Dr. Sneha Pillai, MS',
            'Dr. David Chen, MD',
            'Dr. Priya Deshmukh, MBBS, MS',
        ];

        $caseNumber = sprintf('CASE-%04d', static::$caseSequence++);

        return [
            'case_number' => $caseNumber,
            'patient_reference' => 'PT-REF-' . fake()->unique()->numerify('#####'),
            'surgeon_name' => fake()->randomElement($surgeons),
            'implant_type' => fake()->randomElement($implantTypes),
            'status' => fake()->randomElement(MedicalCase::STATUSES),
            'priority' => fake()->randomElement(MedicalCase::PRIORITIES),
            'surgery_date' => fake()->boolean(80) ? fake()->dateTimeBetween('-1 month', '+2 months')->format('Y-m-d') : null,
            'created_by' => User::factory(),
        ];
    }
}
