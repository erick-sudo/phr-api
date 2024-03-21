# Medical System

## Models

1. Patient
   Represents patients within the medical system.

```php
	// app/Models/Patient.php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Model;

	class Patient extends Model
	{
	    protected $fillable = ['name', 'dob', 'gender', 'address', 'phone', 'email'];
	}
```

2. Doctor
   Represents doctors or healthcare professionals.

```php
    // app/Models/Doctor.php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class Doctor extends Model
    {
        protected $fillable = ['name', 'specialization', 'phone', 'email'];
    }
```

3. Appointment
   Represents appointments between patients and doctors.

```php
    // app/Models/Appointment.php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class Appointment extends Model
    {
        protected $fillable = ['patient_id', 'doctor_id', 'appointment_date', 'reason'];

        public function patient()
        {
            return $this->belongsTo(Patient::class);
        }

        public function doctor()
        {
            return $this->belongsTo(Doctor::class);
        }
    }
```

4. Medical Record
   Represents medical records associated with patients.

```php
    // app/Models/MedicalRecord.php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class MedicalRecord extends Model
    {
        protected $fillable = ['patient_id', 'doctor_id', 'symptoms', 'diagnosis', 'treatment'];

        public function patient()
        {
            return $this->belongsTo(Patient::class);
        }

        public function doctor()
        {
            return $this->belongsTo(Doctor::class);
        }
    }
```

5. Prescription
   Represents prescriptions given to patients by doctors.

```php
    // app/Models/Prescription.php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class Prescription extends Model
    {
        protected $fillable = ['medical_record_id', 'medicine_name', 'dosage', 'frequency'];

        public function medicalRecord()
        {
            return $this->belongsTo(MedicalRecord::class);
        }
    }
```

6. Hospital
   If your system involves managing multiple hospitals or healthcare facilities, a Hospital model could be useful to store information about each hospital.

```php
    // app/Models/Hospital.php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class Hospital extends Model
    {
        protected $fillable = ['name', 'address', 'phone', 'email'];
    }
```

7. Department
   If your system involves departments within hospitals, you might need a Department model to represent different departments such as cardiology, pediatrics, etc.

```php
    // app/Models/Department.php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class Department extends Model
    {
        protected $fillable = ['name', 'hospital_id'];

        public function hospital()
        {
            return $this->belongsTo(Hospital::class);
        }
    }
```

8. LabTest
   If your system handles lab tests, you could have a LabTest model to store information about different types of lab tests.

```php
    // app/Models/LabTest.php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class LabTest extends Model
    {
        protected $fillable = ['name', 'description', 'price'];
    }
```

9. TestResult
   For storing the results of lab tests conducted for patients.

```php
    // app/Models/TestResult.php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class TestResult extends Model
    {
        protected $fillable = ['patient_id', 'lab_test_id', 'result'];

        public function patient()
        {
            return $this->belongsTo(Patient::class);
        }

        public function labTest()
        {
            return $this->belongsTo(LabTest::class);
        }
    }
```

10. Insurance
    If your system involves managing patient insurance information, an Insurance model could be helpful.

```php
    // app/Models/Insurance.php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class Insurance extends Model
    {
        protected $fillable = ['patient_id', 'provider', 'policy_number', 'valid_until'];

        public function patient()
        {
            return $this->belongsTo(Patient::class);
        }
    }
```

Now i need associations for Patient, Doctor, Appointment, LabTest, TestResult, MedicalRecord, Prescription


0741918669
