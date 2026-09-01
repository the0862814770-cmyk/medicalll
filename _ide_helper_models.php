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
 * App\Models\Category
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Supply> $supplies
 * @property-read int|null $supplies_count
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\FirstAidKit
 *
 * @property int $id
 * @property string $kit_code
 * @property string $name
 * @property string $status
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $is_available
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KitItem> $items
 * @property-read int|null $items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KitRequest> $requests
 * @property-read int|null $requests_count
 * @method static \Illuminate\Database\Eloquent\Builder|FirstAidKit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FirstAidKit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FirstAidKit query()
 * @method static \Illuminate\Database\Eloquent\Builder|FirstAidKit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FirstAidKit whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FirstAidKit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FirstAidKit whereKitCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FirstAidKit whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FirstAidKit whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FirstAidKit whereUpdatedAt($value)
 */
	class FirstAidKit extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\KitItem
 *
 * @property int $id
 * @property int $first_aid_kit_id
 * @property int $supply_id
 * @property int $quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\FirstAidKit $kit
 * @property-read \App\Models\Supply $supply
 * @method static \Illuminate\Database\Eloquent\Builder|KitItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KitItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KitItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|KitItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KitItem whereFirstAidKitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KitItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KitItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KitItem whereSupplyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KitItem whereUpdatedAt($value)
 */
	class KitItem extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\KitRequest
 *
 * @property int $id
 * @property string $request_number
 * @property int $user_id
 * @property int $first_aid_kit_id
 * @property string $purpose
 * @property \Illuminate\Support\Carbon $borrow_date
 * @property \Illuminate\Support\Carbon $expected_return_date
 * @property \Illuminate\Support\Carbon|null $actual_return_date
 * @property string $status
 * @property string|null $notes
 * @property string|null $document_path
 * @property int|null $approved_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $activity_name
 * @property int $quantity
 * @property int|null $participants_count
 * @property int|null $executive_approved_by
 * @property \Illuminate\Support\Carbon|null $executive_approved_at
 * @property-read \App\Models\User|null $approver
 * @property-read \App\Models\User|null $executiveApprover
 * @property-read mixed $status_color
 * @property-read mixed $status_label
 * @property-read \App\Models\FirstAidKit $kit
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|KitRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KitRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KitRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|KitRequest whereActivityName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KitRequest whereActualReturnDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KitRequest whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KitRequest whereBorrowDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KitRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KitRequest whereDocumentPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KitRequest whereExecutiveApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KitRequest whereExecutiveApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KitRequest whereExpectedReturnDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KitRequest whereFirstAidKitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KitRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KitRequest whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KitRequest whereParticipantsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KitRequest wherePurpose($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KitRequest whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KitRequest whereRequestNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KitRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KitRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KitRequest whereUserId($value)
 */
	class KitRequest extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\MedicineRequest
 *
 * @property int $id
 * @property string $request_number
 * @property int $user_id
 * @property string $symptoms
 * @property string $status
 * @property string|null $staff_notes
 * @property int|null $approved_by
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $executive_approved_by
 * @property \Illuminate\Support\Carbon|null $executive_approved_at
 * @property-read \App\Models\User|null $approver
 * @property-read \App\Models\User|null $executiveApprover
 * @property-read mixed $status_color
 * @property-read mixed $status_label
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicineRequestItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|MedicineRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MedicineRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MedicineRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|MedicineRequest whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MedicineRequest whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MedicineRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MedicineRequest whereExecutiveApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MedicineRequest whereExecutiveApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MedicineRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MedicineRequest whereRequestNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MedicineRequest whereStaffNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MedicineRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MedicineRequest whereSymptoms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MedicineRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MedicineRequest whereUserId($value)
 */
	class MedicineRequest extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\MedicineRequestItem
 *
 * @property int $id
 * @property int $medicine_request_id
 * @property int $supply_id
 * @property int $quantity_requested
 * @property int $quantity_approved
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\MedicineRequest $request
 * @property-read \App\Models\Supply $supply
 * @method static \Illuminate\Database\Eloquent\Builder|MedicineRequestItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MedicineRequestItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MedicineRequestItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|MedicineRequestItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MedicineRequestItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MedicineRequestItem whereMedicineRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MedicineRequestItem whereQuantityApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MedicineRequestItem whereQuantityRequested($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MedicineRequestItem whereSupplyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MedicineRequestItem whereUpdatedAt($value)
 */
	class MedicineRequestItem extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Supply
 *
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string $code
 * @property string $unit
 * @property int $min_stock
 * @property string|null $manufacturer
 * @property string|null $storage_location
 * @property string|null $image_path
 * @property string|null $description
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SupplyLot> $activeLots
 * @property-read int|null $active_lots_count
 * @property-read \App\Models\Category $category
 * @property-read mixed $detailed_status
 * @property-read string $image_url
 * @property-read mixed $is_low_stock
 * @property-read mixed $nearest_expiry
 * @property-read mixed $stock_percent
 * @property-read mixed $total_stock
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KitItem> $kitItems
 * @property-read int|null $kit_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SupplyLot> $lots
 * @property-read int|null $lots_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicineRequestItem> $requestItems
 * @property-read int|null $request_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SupplyTransaction> $transactions
 * @property-read int|null $transactions_count
 * @method static \Illuminate\Database\Eloquent\Builder|Supply newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Supply newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Supply query()
 * @method static \Illuminate\Database\Eloquent\Builder|Supply whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supply whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supply whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supply whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supply whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supply whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supply whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supply whereManufacturer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supply whereMinStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supply whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supply whereStorageLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supply whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supply whereUpdatedAt($value)
 */
	class Supply extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SupplyLot
 *
 * @property int $id
 * @property int $supply_id
 * @property string $lot_number
 * @property int $quantity
 * @property int $remaining_quantity
 * @property \Illuminate\Support\Carbon $expiry_date
 * @property \Illuminate\Support\Carbon $received_date
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $is_expired
 * @property-read mixed $is_near_expiry
 * @property-read \App\Models\Supply $supply
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SupplyTransaction> $transactions
 * @property-read int|null $transactions_count
 * @method static \Illuminate\Database\Eloquent\Builder|SupplyLot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupplyLot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupplyLot query()
 * @method static \Illuminate\Database\Eloquent\Builder|SupplyLot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplyLot whereExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplyLot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplyLot whereLotNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplyLot whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplyLot whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplyLot whereReceivedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplyLot whereRemainingQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplyLot whereSupplyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplyLot whereUpdatedAt($value)
 */
	class SupplyLot extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SupplyTransaction
 *
 * @property int $id
 * @property int $supply_id
 * @property int|null $supply_lot_id
 * @property string $type
 * @property int $quantity
 * @property string|null $notes
 * @property string|null $reference
 * @property int $performed_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $type_color
 * @property-read mixed $type_label
 * @property-read \App\Models\SupplyLot|null $lot
 * @property-read \App\Models\User $performer
 * @property-read \App\Models\Supply $supply
 * @method static \Illuminate\Database\Eloquent\Builder|SupplyTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupplyTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupplyTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|SupplyTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplyTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplyTransaction whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplyTransaction wherePerformedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplyTransaction whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplyTransaction whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplyTransaction whereSupplyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplyTransaction whereSupplyLotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplyTransaction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplyTransaction whereUpdatedAt($value)
 */
	class SupplyTransaction extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $profile_photo_path
 * @property string $role
 * @property string|null $phone
 * @property string|null $student_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicineRequest> $approvedRequests
 * @property-read int|null $approved_requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KitRequest> $kitRequests
 * @property-read int|null $kit_requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicineRequest> $medicineRequests
 * @property-read int|null $medicine_requests_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SupplyTransaction> $transactions
 * @property-read int|null $transactions_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfilePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

