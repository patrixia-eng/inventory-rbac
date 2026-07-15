<?php
namespace App\Policies; 
  
  use App\Models\InventoryItem; 
  use App\Models\User; 
    
  class InventoryItemPolicy 
  { 
      /** 
       * Any authenticated user may view the inventory LISTING. 
       * (No permission governs the listing; can_view covers item DETAILS only.) 
       */ 
      public function viewAny(User $user): bool 
      { 
          return true; 
    } 
  
    /** 
     * View an individual inventory item's details. 
     */ 
    public function view(User $user, InventoryItem $inventoryItem): bool 
    { 
        return $user->hasPermission('can_view'); 
    } 
  
    /** 
     * Create new inventory entries. 
     */ 
    public function create(User $user): bool 
    { 
        return $user->hasPermission('can_create'); 
    } 
  
    /** 
     * Update existing inventory entries. 
     */ 
    public function update(User $user, InventoryItem $inventoryItem): bool 
    { 
        return $user->hasPermission('can_update'); 
    } 
  
    /** 
     * Remove existing inventory entries. 
     */ 
    public function delete(User $user, InventoryItem $inventoryItem): bool 
    { 
        return $user->hasPermission('can_remove'); 
    } 
} 