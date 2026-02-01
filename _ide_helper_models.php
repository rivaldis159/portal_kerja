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
 * @property int $user_id
 * @property int $link_id
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property \Illuminate\Support\Carbon $accessed_at
 * @property-read \App\Models\Link $link
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccessLog forLink($linkId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccessLog forUser($userId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccessLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccessLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccessLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccessLog recent($days = 7)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccessLog whereAccessedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccessLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccessLog whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccessLog whereLinkId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccessLog whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccessLog whereUserId($value)
 */
	class AccessLog extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $team_id
 * @property string $title
 * @property string $content
 * @property bool $is_active
 * @property string $priority
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $priority_color
 * @property-read \App\Models\Team|null $team
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Announcement active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Announcement forTeam($teamId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Announcement global()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Announcement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Announcement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Announcement orderedByPriority()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Announcement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Announcement whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Announcement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Announcement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Announcement whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Announcement wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Announcement whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Announcement whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Announcement whereUpdatedAt($value)
 */
	class Announcement extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $is_active
 * @property string $name
 * @property string $slug
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Link> $links
 * @property-read int|null $links_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category ordered()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereUpdatedAt($value)
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string|null $nip
 * @property string|null $nik
 * @property string|null $pangkat_golongan
 * @property string|null $jabatan
 * @property string|null $masa_kerja
 * @property string|null $pendidikan_terakhir
 * @property string|null $tempat_lahir
 * @property string|null $tanggal_lahir
 * @property string|null $alamat_tinggal
 * @property string|null $status_perkawinan
 * @property string|null $nama_pasangan
 * @property string|null $nomor_rekening
 * @property string $bank_name
 * @property string|null $email_kantor
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $nip_lama
 * @property string|null $tmt_pangkat
 * @property int $masa_kerja_tahun
 * @property int $masa_kerja_bulan
 * @property string|null $pendidikan_strata
 * @property string|null $pendidikan_jurusan
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeDetail whereAlamatTinggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeDetail whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeDetail whereEmailKantor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeDetail whereJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeDetail whereMasaKerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeDetail whereMasaKerjaBulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeDetail whereMasaKerjaTahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeDetail whereNamaPasangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeDetail whereNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeDetail whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeDetail whereNipLama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeDetail whereNomorRekening($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeDetail wherePangkatGolongan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeDetail wherePendidikanJurusan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeDetail wherePendidikanStrata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeDetail wherePendidikanTerakhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeDetail whereStatusPerkawinan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeDetail whereTanggalLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeDetail whereTempatLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeDetail whereTmtPangkat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeDetail whereUserId($value)
 */
	class EmployeeDetail extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $team_id
 * @property int $is_bps_pusat
 * @property int|null $category_id
 * @property string $title
 * @property string $url
 * @property string $target
 * @property int $is_vpn_required
 * @property int $is_public
 * @property string|null $description
 * @property string|null $icon
 * @property string $color
 * @property int $is_active
 * @property int $order
 * @property int $open_new_tab
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category|null $category
 * @property-read \App\Models\Team $team
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Link newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Link newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Link query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Link whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Link whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Link whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Link whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Link whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Link whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Link whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Link whereIsBpsPusat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Link whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Link whereIsVpnRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Link whereOpenNewTab($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Link whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Link whereTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Link whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Link whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Link whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Link whereUrl($value)
 */
	class Link extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $color
 * @property string|null $icon
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Announcement> $announcements
 * @property-read int|null $announcements_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Link> $links
 * @property-read int|null $links_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereUpdatedAt($value)
 */
	class Team extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $role
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property int $is_active
 * @property string|null $last_login
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $team_id
 * @property-read \App\Models\EmployeeDetail|null $employeeDetail
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Team> $teams
 * @property-read int|null $teams_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

