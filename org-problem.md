
# Organization Dashboard Problem Context

## routes/web.php

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\EventPageController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\MessagesController;

// ===============================
// FRONTEND ROUTES
// ===============================

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Static Pages
Route::view('/about', 'pages.about')->name('about');

// ===============================
// AUTHENTICATION ROUTES
// ===============================

// Login Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login')
    ->middleware('guest');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.submit')
    ->middleware('guest');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// Registration Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])
    ->name('register')
    ->middleware('guest');

Route::post('/register/volunteer', [RegisterController::class, 'registerVolunteer'])
    ->name('register.volunteer')
    ->middleware('guest');

Route::post('/register/organization', [RegisterController::class, 'registerOrganization'])
    ->name('register.organization')
    ->middleware('guest');

   // ===============================
// PROTECTED ROUTES (Require Authentication)
// ===============================
Route::middleware(['auth'])->group(function () {
    
    // Volunteer Routes
    Route::prefix('volunteer')->name('volunteer.')->group(function () {
        Route::get('/dashboard', [VolunteerController::class, 'dashboard'])->name('dashboard');
        Route::get('/events', [VolunteerController::class, 'events'])->name('events');
        
        // Profile routes
        Route::get('/profile', [VolunteerController::class, 'profile'])->name('profile');
        Route::put('/profile/update', [VolunteerController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/info', [VolunteerController::class, 'updateInfo'])->name('info.update');
        
        // Account routes
        Route::get('/account', [VolunteerController::class, 'account'])->name('account');
        Route::put('/account/password', [VolunteerController::class, 'updatePassword'])->name('password.update');
        Route::delete('/account/deactivate', [VolunteerController::class, 'deactivateAccount'])->name('account.deactivate');
        
        Route::get('/messages', [VolunteerController::class, 'messages'])->name('messages');
    });

    // Organization Dashboard (placeholder for now)
    Route::get('/organization/dashboard', function () {
        return view('organization.dashboard');
    })->name('organization.dashboard');

    // Admin Dashboard (placeholder for now)
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});


    

// ===============================
// EVENTS PAGES
// ===============================
Route::get('/events', [EventPageController::class, 'index'])->name('events');
Route::get('/events/{id}', [EventPageController::class, 'show'])->name('events.show');

// ===============================
// PROTECTED ROUTES (Require Authentication)
// ===============================
Route::middleware(['auth'])->group(function () {

    // Organization Dashboard (placeholder for now)
    Route::get('/organization/dashboard', function () {
        return view('organization.dashboard');
    })->name('organization.dashboard');

    // Admin Dashboard (placeholder for now)
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});
```

---

## app/Models/Organization.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $primaryKey = 'organization_id';
    protected $fillable = [
        'user_id', 'org_name', 'org_type', 'registration_number', 'contact_person',
        'phone', 'address', 'description', 'logo_image', 'is_verified', 'verified_at'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function events() {
        return $this->hasMany(Event::class, 'organization_id');
    }

    public function verifications() {
        return $this->hasMany(OrganizationVerification::class, 'organization_id');
    }
}
```

---

## app/Models/Event.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $primaryKey = 'event_id';
    protected $fillable = [
        'organization_id', 'event_name', 'description', 'category',
        'event_date', 'start_time', 'end_time', 'location',
        'max_volunteers', 'registered_count', 'status'
    ];

    public function organization() {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function registrations() {
        return $this->hasMany(EventRegistration::class, 'event_id');
    }

    public function skills() {
        return $this->belongsToMany(Skill::class, 'event_skills', 'event_id', 'skill_id')
                    ->withPivot('is_required')
                    ->withTimestamps();
    }

    public function images() {
        return $this->hasMany(EventImage::class, 'event_id');
    }
}
```

---

## app/Models/EventRegistration.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    use HasFactory;

    protected $table = 'event_registrations';

    protected $primaryKey = 'registration_id';
    protected $fillable = [
        'event_id', 'volunteer_id', 'registered_at', 'status', 'notes',
        'hours_contributed', 'certificate_issued'
    ];

    public function volunteer() {
        return $this->belongsTo(Volunteer::class, 'volunteer_id');
    }

    public function event() {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function attendance() {
        return $this->hasMany(Attendance::class, 'registration_id');
    }

    public function feedback() {
        return $this->hasOne(Feedback::class, 'registration_id');
    }
}
```

---

## database/migrations/2025_10_29_135338_create_organizations_table.php

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id('organization_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->string('org_name');
            $table->string('org_type');
            $table->string('registration_number')->nullable();
            $table->string('contact_person');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->text('description')->nullable();
            $table->string('logo_image')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('organizations');
    }
};
```

---

## database/migrations/2025_10_29_135437_create_events_table.php

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('events', function (Blueprint $table) {
            $table->id('event_id');
            $table->foreignId('organization_id')->constrained('organizations', 'organization_id')->onDelete('cascade');
            $table->string('event_name');
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->date('event_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('location');
            $table->integer('max_volunteers');
            $table->integer('registered_count')->default(0);
            $table->string('status')->default('open');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('events');
    }
};
```

---

## database/migrations/2025_10_29_135732_create_event_registrations_table.php

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id('registration_id');
            $table->foreignId('event_id')->constrained('events', 'event_id')->onDelete('cascade');
            $table->foreignId('volunteer_id')->constrained('volunteers', 'volunteer_id')->onDelete('cascade');
            $table->timestamp('registered_at')->useCurrent();
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->integer('hours_contributed')->default(0);
            $table->boolean('certificate_issued')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('event_registrations');
    }
};
```
