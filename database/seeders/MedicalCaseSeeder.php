<?php

namespace Database\Seeders;

use App\Models\MedicalCase;
use App\Models\User;
use Illuminate\Database\Seeder;

class MedicalCaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', User::ROLE_ADMIN)->first() ?? User::factory()->admin()->create();

        $cases = [
            [
                'case_number' => 'CASE-1001',
                'patient_reference' => 'JM-PT-88201',
                'surgeon_name' => 'Dr. Rajesh Jajal, MD',
                'implant_type' => 'Titanium 3D-Printed Cranial Mesh (Frontal Defect)',
                'status' => MedicalCase::STATUS_PLANNING,
                'priority' => MedicalCase::PRIORITY_HIGH,
                'surgery_date' => now()->addDays(5)->format('Y-m-d'),
            ],
            [
                'case_number' => 'CASE-1002',
                'patient_reference' => 'JM-PT-88202',
                'surgeon_name' => 'Dr. Meera Iyer, MS (Ortho)',
                'implant_type' => 'PEEK Interbody Spinal Fusion Cage (L4-L5)',
                'status' => MedicalCase::STATUS_IN_REVIEW,
                'priority' => MedicalCase::PRIORITY_MEDIUM,
                'surgery_date' => now()->addDays(8)->format('Y-m-d'),
            ],
            [
                'case_number' => 'CASE-1003',
                'patient_reference' => 'JM-PT-88203',
                'surgeon_name' => 'Dr. Vikram Malhotra, MCh (Neuro)',
                'implant_type' => 'Custom Patient-Specific Mandibular Reconstruction Plate',
                'status' => MedicalCase::STATUS_APPROVED,
                'priority' => MedicalCase::PRIORITY_HIGH,
                'surgery_date' => now()->addDays(3)->format('Y-m-d'),
            ],
            [
                'case_number' => 'CASE-1004',
                'patient_reference' => 'JM-PT-88204',
                'surgeon_name' => 'Dr. Sarah Jenkins, FRCS',
                'implant_type' => 'Ceramic-on-Polyethylene Total Hip Acetabular Cup',
                'status' => MedicalCase::STATUS_COMPLETED,
                'priority' => MedicalCase::PRIORITY_LOW,
                'surgery_date' => now()->subDays(10)->format('Y-m-d'),
            ],
            [
                'case_number' => 'CASE-1005',
                'patient_reference' => 'JM-PT-88205',
                'surgeon_name' => 'Dr. Amit Kulkarni, DNB',
                'implant_type' => 'Bioresorbable Coronary Vascular Scaffold',
                'status' => MedicalCase::STATUS_DRAFT,
                'priority' => MedicalCase::PRIORITY_MEDIUM,
                'surgery_date' => null,
            ],
            [
                'case_number' => 'CASE-1006',
                'patient_reference' => 'JM-PT-88206',
                'surgeon_name' => 'Dr. Sneha Pillai, MS',
                'implant_type' => 'Trabecular Titanium Porous Knee Tibial Tray',
                'status' => MedicalCase::STATUS_PLANNING,
                'priority' => MedicalCase::PRIORITY_MEDIUM,
                'surgery_date' => now()->addDays(14)->format('Y-m-d'),
            ],
            [
                'case_number' => 'CASE-1007',
                'patient_reference' => 'JM-PT-88207',
                'surgeon_name' => 'Dr. David Chen, MD',
                'implant_type' => 'Anterior Cervical Discectomy and Fusion Plate (C5-C6)',
                'status' => MedicalCase::STATUS_APPROVED,
                'priority' => MedicalCase::PRIORITY_HIGH,
                'surgery_date' => now()->addDays(2)->format('Y-m-d'),
            ],
            [
                'case_number' => 'CASE-1008',
                'patient_reference' => 'JM-PT-88208',
                'surgeon_name' => 'Dr. Priya Deshmukh, MBBS, MS',
                'implant_type' => 'Reverse Shoulder Modular Glenosphere Assembly',
                'status' => MedicalCase::STATUS_IN_REVIEW,
                'priority' => MedicalCase::PRIORITY_LOW,
                'surgery_date' => now()->addDays(21)->format('Y-m-d'),
            ],
            [
                'case_number' => 'CASE-1009',
                'patient_reference' => 'JM-PT-88209',
                'surgeon_name' => 'Dr. Rajesh Jajal, MD',
                'implant_type' => 'Custom Pediatric Femoral Osteotomy Guide & Locking Plate',
                'status' => MedicalCase::STATUS_COMPLETED,
                'priority' => MedicalCase::PRIORITY_HIGH,
                'surgery_date' => now()->subDays(4)->format('Y-m-d'),
            ],
            [
                'case_number' => 'CASE-1010',
                'patient_reference' => 'JM-PT-88210',
                'surgeon_name' => 'Dr. Amit Kulkarni, DNB',
                'implant_type' => 'Cobalt-Chrome Unicondylar Knee Femoral Component',
                'status' => MedicalCase::STATUS_CANCELLED,
                'priority' => MedicalCase::PRIORITY_LOW,
                'surgery_date' => null,
            ],
            [
                'case_number' => 'CASE-1011',
                'patient_reference' => 'JM-PT-88211',
                'surgeon_name' => 'Dr. Meera Iyer, MS (Ortho)',
                'implant_type' => 'Dual-Mobility Acetabular System with Porous Coating',
                'status' => MedicalCase::STATUS_PLANNING,
                'priority' => MedicalCase::PRIORITY_MEDIUM,
                'surgery_date' => now()->addDays(12)->format('Y-m-d'),
            ],
            [
                'case_number' => 'CASE-1012',
                'patient_reference' => 'JM-PT-88212',
                'surgeon_name' => 'Dr. Vikram Malhotra, MCh (Neuro)',
                'implant_type' => 'Titanium Cranioplasty Patient-Specific Implant (Parietal)',
                'status' => MedicalCase::STATUS_IN_REVIEW,
                'priority' => MedicalCase::PRIORITY_HIGH,
                'surgery_date' => now()->addDays(7)->format('Y-m-d'),
            ],
            [
                'case_number' => 'CASE-1013',
                'patient_reference' => 'JM-PT-88213',
                'surgeon_name' => 'Dr. Sarah Jenkins, FRCS',
                'implant_type' => 'Ceramic Femoral Head (36mm) on Taperloc Hip Stem',
                'status' => MedicalCase::STATUS_APPROVED,
                'priority' => MedicalCase::PRIORITY_MEDIUM,
                'surgery_date' => now()->addDays(9)->format('Y-m-d'),
            ],
            [
                'case_number' => 'CASE-1014',
                'patient_reference' => 'JM-PT-88214',
                'surgeon_name' => 'Dr. Sneha Pillai, MS',
                'implant_type' => 'Bipolar Radial Head Prosthesis with Textured Stem',
                'status' => MedicalCase::STATUS_DRAFT,
                'priority' => MedicalCase::PRIORITY_LOW,
                'surgery_date' => null,
            ],
            [
                'case_number' => 'CASE-1015',
                'patient_reference' => 'JM-PT-88215',
                'surgeon_name' => 'Dr. David Chen, MD',
                'implant_type' => 'Thoracolumbar Posterior Spinal Fixation Rods & Pedicle Screws',
                'status' => MedicalCase::STATUS_COMPLETED,
                'priority' => MedicalCase::PRIORITY_HIGH,
                'surgery_date' => now()->subDays(18)->format('Y-m-d'),
            ],
            [
                'case_number' => 'CASE-1016',
                'patient_reference' => 'JM-PT-88216',
                'surgeon_name' => 'Dr. Priya Deshmukh, MBBS, MS',
                'implant_type' => 'Patient-Specific 3D Orbital Floor Titanium Mesh',
                'status' => MedicalCase::STATUS_PLANNING,
                'priority' => MedicalCase::PRIORITY_HIGH,
                'surgery_date' => now()->addDays(16)->format('Y-m-d'),
            ],
            [
                'case_number' => 'CASE-1017',
                'patient_reference' => 'JM-PT-88217',
                'surgeon_name' => 'Dr. Rajesh Jajal, MD',
                'implant_type' => 'Hydrodynamic Ventriculoperitoneal Shunt Valve System',
                'status' => MedicalCase::STATUS_IN_REVIEW,
                'priority' => MedicalCase::PRIORITY_MEDIUM,
                'surgery_date' => now()->addDays(11)->format('Y-m-d'),
            ],
            [
                'case_number' => 'CASE-1018',
                'patient_reference' => 'JM-PT-88218',
                'surgeon_name' => 'Dr. Amit Kulkarni, DNB',
                'implant_type' => 'Aortic Stent Graft with Tri-Fabriated Bifurcation',
                'status' => MedicalCase::STATUS_APPROVED,
                'priority' => MedicalCase::PRIORITY_HIGH,
                'surgery_date' => now()->addDays(4)->format('Y-m-d'),
            ],
            [
                'case_number' => 'CASE-1019',
                'patient_reference' => 'JM-PT-88219',
                'surgeon_name' => 'Dr. Meera Iyer, MS (Ortho)',
                'implant_type' => 'Proximal Humerus Internal Locking Osteosyntheses Plate',
                'status' => MedicalCase::STATUS_COMPLETED,
                'priority' => MedicalCase::PRIORITY_MEDIUM,
                'surgery_date' => now()->subDays(6)->format('Y-m-d'),
            ],
            [
                'case_number' => 'CASE-1020',
                'patient_reference' => 'JM-PT-88220',
                'surgeon_name' => 'Dr. Sarah Jenkins, FRCS',
                'implant_type' => 'Total Talar Body 3D-Printed Prosthesis with Articular Surface',
                'status' => MedicalCase::STATUS_PLANNING,
                'priority' => MedicalCase::PRIORITY_MEDIUM,
                'surgery_date' => now()->addDays(20)->format('Y-m-d'),
            ],
            [
                'case_number' => 'CASE-1021',
                'patient_reference' => 'JM-PT-88221',
                'surgeon_name' => 'Dr. Vikram Malhotra, MCh (Neuro)',
                'implant_type' => 'Custom Titanium Temporal Defect Cranioplasty Plate',
                'status' => MedicalCase::STATUS_APPROVED,
                'priority' => MedicalCase::PRIORITY_HIGH,
                'surgery_date' => now()->addDays(6)->format('Y-m-d'),
            ],
            [
                'case_number' => 'CASE-1022',
                'patient_reference' => 'JM-PT-88222',
                'surgeon_name' => 'Dr. Sneha Pillai, MS',
                'implant_type' => 'Modular Revision Hip Acetabular Augment and Cage',
                'status' => MedicalCase::STATUS_IN_REVIEW,
                'priority' => MedicalCase::PRIORITY_MEDIUM,
                'surgery_date' => now()->addDays(15)->format('Y-m-d'),
            ],
            [
                'case_number' => 'CASE-1023',
                'patient_reference' => 'JM-PT-88223',
                'surgeon_name' => 'Dr. David Chen, MD',
                'implant_type' => 'Bioactive Glass Vertebral Body Replacement Mesh',
                'status' => MedicalCase::STATUS_DRAFT,
                'priority' => MedicalCase::PRIORITY_LOW,
                'surgery_date' => null,
            ],
            [
                'case_number' => 'CASE-1024',
                'patient_reference' => 'JM-PT-88224',
                'surgeon_name' => 'Dr. Priya Deshmukh, MBBS, MS',
                'implant_type' => 'Custom Zygomatic Arch Reconstruction Implant',
                'status' => MedicalCase::STATUS_COMPLETED,
                'priority' => MedicalCase::PRIORITY_HIGH,
                'surgery_date' => now()->subDays(15)->format('Y-m-d'),
            ],
            [
                'case_number' => 'CASE-1025',
                'patient_reference' => 'JM-PT-88225',
                'surgeon_name' => 'Dr. Rajesh Jajal, MD',
                'implant_type' => 'Titanium Sternum Reconstruction Plate for Trauma Fixation',
                'status' => MedicalCase::STATUS_PLANNING,
                'priority' => MedicalCase::PRIORITY_HIGH,
                'surgery_date' => now()->addDays(10)->format('Y-m-d'),
            ],
        ];

        foreach ($cases as $caseData) {
            MedicalCase::updateOrCreate(
                ['case_number' => $caseData['case_number']],
                array_merge($caseData, ['created_by' => $admin->id])
            );
        }
    }
}
