<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int|null $user_id
 * @property string $action
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property array<array-key, mixed>|null $metadata
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereUserId($value)
 */
	class ActivityLog extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nisn
 * @property string $nama_lengkap
 * @property string $jenis_kelamin
 * @property string $tempat_lahir
 * @property \Illuminate\Support\Carbon $tanggal_lahir
 * @property int $tahun_lulus
 * @property string $no_hp
 * @property string $alamat
 * @property string|null $foto_profile
 * @property string $jurusan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereFotoProfile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereJenisKelamin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereJurusan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereNamaLengkap($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereNisn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereNoHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereTahunLulus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereTanggalLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereTempatLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereUpdatedAt($value)
 */
	class Alumni extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $user_id
 * @property string $name
 * @property string $job_title
 * @property string|null $graduation_year
 * @property string $story
 * @property string|null $photo
 * @property int $is_featured
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $initials
 * @property-read string|null $photo_url
 * @property-read string $status_badge
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlumniStory approved()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlumniStory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlumniStory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlumniStory pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlumniStory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlumniStory rejected()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlumniStory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlumniStory whereGraduationYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlumniStory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlumniStory whereIsFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlumniStory whereJobTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlumniStory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlumniStory wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlumniStory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlumniStory whereStory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlumniStory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlumniStory whereUserId($value)
 */
	class AlumniStory extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $company_id
 * @property int|null $user_id
 * @property string $company_name
 * @property string|null $industry
 * @property string|null $address
 * @property string|null $contact_person
 * @property string|null $phone
 * @property string|null $website
 * @property int $is_verified
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Job> $jobs
 * @property-read int|null $jobs_count
 * @property-read \App\Models\User|null $user
 * @property-read \App\Models\User|null $userProfile
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereContactPerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereIndustry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereWebsite($value)
 */
	class Company extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property string|null $location
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property int $capacity
 * @property string|null $organizer
 * @property string|null $category
 * @property string|null $image
 * @property bool $is_published
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $registration_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EventRegistration> $registrations
 * @property-read int|null $registrations_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereIsPublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereOrganizer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereUpdatedAt($value)
 */
	class Event extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $event_registration_id
 * @property string $event_id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string|null $institution
 * @property string|null $position
 * @property string $status
 * @property string|null $admin_notes
 * @property \Illuminate\Support\Carbon $registered_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventRegistration newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventRegistration newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventRegistration query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventRegistration whereAdminNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventRegistration whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventRegistration whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventRegistration whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventRegistration whereEventRegistrationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventRegistration whereInstitution($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventRegistration whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventRegistration wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventRegistration wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventRegistration whereRegisteredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventRegistration whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventRegistration whereUpdatedAt($value)
 */
	class EventRegistration extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $year
 * @property string|null $label
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GraduationYear newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GraduationYear newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GraduationYear query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GraduationYear whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GraduationYear whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GraduationYear whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GraduationYear whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GraduationYear whereYear($value)
 */
	class GraduationYear extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $job_id
 * @property int $company_id
 * @property int|null $major_id
 * @property int|null $admin_id
 * @property string $title
 * @property string|null $description
 * @property string|null $requirements
 * @property string|null $location
 * @property string|null $salary
 * @property string $source
 * @property string|null $skill_required
 * @property string|null $benefits
 * @property string|null $responsibilities
 * @property string|null $logo
 * @property string|null $job_type
 * @property string $status
 * @property string $visibility
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $posted_at
 * @property \Illuminate\Support\Carbon|null $expired_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $admin
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\JobApplication> $applications
 * @property-read int|null $applications_count
 * @property-read \App\Models\Company $company
 * @property-read string $company_name
 * @property-read bool $is_active_job
 * @property-read string|null $logo_url
 * @property-read \App\Models\Major|null $major
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SavedJob> $savedByStudents
 * @property-read int|null $saved_by_students_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job alumniOnly()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job public()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job visibleFor($user)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereBenefits($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereJobId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereJobType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereMajorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job wherePostedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereRequirements($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereResponsibilities($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereSkillRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereVisibility($value)
 */
	class Job extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $job_application_id
 * @property int $job_id
 * @property int|null $student_id
 * @property string|null $cover_letter
 * @property string|null $additional_file
 * @property string|null $full_name
 * @property string|null $email
 * @property string|null $phone_number
 * @property string|null $admin_notes
 * @property string $status
 * @property \Illuminate\Support\Carbon $application_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Job $job
 * @property-read \App\Models\Student|null $student
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobApplication newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobApplication newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobApplication query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobApplication whereAdditionalFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobApplication whereAdminNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobApplication whereApplicationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobApplication whereCoverLetter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobApplication whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobApplication whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobApplication whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobApplication whereJobApplicationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobApplication whereJobId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobApplication wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobApplication whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobApplication whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobApplication whereUpdatedAt($value)
 */
	class JobApplication extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $nip
 * @property string $nama_lengkap
 * @property string|null $tanda_tangan_digital
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KepalaBkk newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KepalaBkk newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KepalaBkk query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KepalaBkk whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KepalaBkk whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KepalaBkk whereNamaLengkap($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KepalaBkk whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KepalaBkk whereTandaTanganDigital($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KepalaBkk whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KepalaBkk whereUserId($value)
 */
	class KepalaBkk extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $nip
 * @property string $nama_lengkap
 * @property string $periode_jabatan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KepalaSekolah newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KepalaSekolah newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KepalaSekolah query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KepalaSekolah whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KepalaSekolah whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KepalaSekolah whereNamaLengkap($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KepalaSekolah whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KepalaSekolah wherePeriodeJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KepalaSekolah whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KepalaSekolah whereUserId($value)
 */
	class KepalaSekolah extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Major newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Major newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Major query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Major whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Major whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Major whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Major whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Major whereUpdatedAt($value)
 */
	class Major extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $category
 * @property string $content
 * @property string|null $tags
 * @property string|null $excerpt
 * @property string|null $image
 * @property int|null $author_id
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property bool $is_published
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $author
 * @property-read mixed $reading_time
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereExcerpt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereIsPublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereUpdatedAt($value)
 */
	class News extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $type
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property string $data
 * @property string|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereNotifiableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereNotifiableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereUpdatedAt($value)
 */
	class Notification extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $otp
 * @property \Illuminate\Support\Carbon $expires_at
 * @property int $valid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtpCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtpCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtpCode query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtpCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtpCode whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtpCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtpCode whereOtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtpCode whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtpCode whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtpCode whereValid($value)
 */
	class OtpCode extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $display_name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereUpdatedAt($value)
 */
	class Permission extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $student_id
 * @property string $judul_projek
 * @property string $deskripsi
 * @property string|null $link_projek
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Student|null $student
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio whereJudulProjek($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio whereLinkProjek($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio whereUpdatedAt($value)
 */
	class Portfolio extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nisn
 * @property string $nama_lengkap
 * @property string $jenis_kelamin
 * @property string $tempat_lahir
 * @property \Illuminate\Support\Carbon $tanggal_lahir
 * @property int $tahun_lulus
 * @property string $no_hp
 * @property string $alamat
 * @property string|null $foto_profile
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publik newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publik newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publik query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publik whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publik whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publik whereFotoProfile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publik whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publik whereJenisKelamin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publik whereNamaLengkap($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publik whereNisn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publik whereNoHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publik whereTahunLulus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publik whereTanggalLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publik whereTempatLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publik whereUpdatedAt($value)
 */
	class Publik extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $display_name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereUpdatedAt($value)
 */
	class Role extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $job_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Job $job
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SavedJob newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SavedJob newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SavedJob query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SavedJob whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SavedJob whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SavedJob whereJobId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SavedJob whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SavedJob whereUserId($value)
 */
	class SavedJob extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $school_name
 * @property string|null $school_address
 * @property string|null $logo_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $site_title
 * @property string|null $site_description
 * @property string|null $tagline
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $address
 * @property string|null $facebook
 * @property string|null $instagram
 * @property string|null $twitter
 * @property string|null $youtube
 * @property string|null $logo
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolProfile whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolProfile whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolProfile whereFacebook($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolProfile whereInstagram($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolProfile whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolProfile whereLogoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolProfile wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolProfile whereSchoolAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolProfile whereSchoolName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolProfile whereSiteDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolProfile whereSiteTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolProfile whereTagline($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolProfile whereTwitter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolProfile whereYoutube($value)
 */
	class SchoolProfile extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Student> $students
 * @property-read int|null $students_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Skill newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Skill newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Skill query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Skill whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Skill whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Skill whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Skill whereUpdatedAt($value)
 */
	class Skill extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $student_id
 * @property int $user_id
 * @property string $nis
 * @property string|null $nisn
 * @property string $full_name
 * @property string|null $gender
 * @property string|null $birth_info
 * @property string $major
 * @property string $graduation_year
 * @property int $alumni_flag
 * @property string $career_path
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $resume_url
 * @property string|null $profile_picture
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $graduation_label
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\JobApplication> $jobApplications
 * @property-read int|null $job_applications_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student alumniFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereAlumniFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereBirthInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereCareerPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereGraduationYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereMajor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereNis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereNisn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereProfilePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereResumeUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereUserId($value)
 */
	class Student extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $nama_lengkap
 * @property string|null $kontak
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuperAdmin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuperAdmin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuperAdmin query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuperAdmin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuperAdmin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuperAdmin whereKontak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuperAdmin whereNamaLengkap($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuperAdmin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuperAdmin whereUserId($value)
 */
	class SuperAdmin extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $student_id
 * @property string $status_saat_ini
 * @property string|null $nama_instansi
 * @property string|null $tgl_mulai_masuk
 * @property numeric|null $pendapatan_bulanan
 * @property string|null $keselarasan_jurusan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Student $student
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TracerStudy newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TracerStudy newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TracerStudy query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TracerStudy whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TracerStudy whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TracerStudy whereKeselarasanJurusan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TracerStudy whereNamaInstansi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TracerStudy wherePendapatanBulanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TracerStudy whereStatusSaatIni($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TracerStudy whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TracerStudy whereTglMulaiMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TracerStudy whereUpdatedAt($value)
 */
	class TracerStudy extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $social_id
 * @property string|null $social_provider
 * @property string|null $avatar
 * @property int|null $role_id
 * @property int|null $userable_id
 * @property string|null $userable_type
 * @property bool $is_active
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $avatar_url
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OtpCode> $otpCodes
 * @property-read int|null $otp_codes_count
 * @property-read \App\Models\Role|null $role
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SavedJob> $savedJobs
 * @property-read int|null $saved_jobs_count
 * @property-read \App\Models\Student|null $student
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent|null $userable
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereSocialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereSocialProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUserableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUserableType($value)
 */
	class User extends \Eloquent {}
}

