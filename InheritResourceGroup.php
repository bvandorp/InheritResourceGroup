<?php
/*
	
	Simple modx plugin that checks if the resource has a parent that is part of a resource group and automatically adds the child resource to this resource group.
	
	Usage: 
	1. Create new plugin and name it InheritResourceGroup
	2. Copy/Paste the contents of this file
	3. At system events check the checkbox at onDocFormSave
	4. Save a child-resource to test it (which has a parent with a resourcegroup set)
	
*/	
	
	
//Check if resource has parent
if ($resource->get('parent')) {

	//Get ID of parent resource
	$currentParentId = $resource->get('parent');

	// Get the collection of resource group objects for this resource.
  $childId = $resource->get('id');
  $childResourceGroupCollection = $modx->getCollection('modResourceGroupResource', array('document' => $childId));

  // Build an array of resource group IDs for this resource
  $childResourceGroups = array();
  foreach($childResourceGroupCollection as $group) {
    $childResourceGroups[] = $group->get('document_group');
  }

  // Check if parent is in a resource group
  if ($parentResourceGroups = $modx->getCollection('modResourceGroupResource',array('document' => $currentParentId))) {

    // For each of the parent's resource groups, get the group ID and join the 
    // new resource to that group.
    foreach($parentResourceGroups as $group) {
      $groupId = $group->get('document_group');

      // If the resource is not already in the RG, join the group.
      if ( ! in_array($groupId, $childResourceGroups)) {
        $resource->joinGroup($groupId);
      }
    }

  }

}