<?php
/* Copyright (C) 2007-2015 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) ---Put here your own copyright and developer email---
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *   	\file       beerresaler/beers_card.php
 *		\ingroup    beerresaler
 *		\brief      This file is an example of a php page
 *					Initialy built by build_class_from_table on 2017-03-10 09:48
 */

//if (! defined('NOREQUIREUSER'))  define('NOREQUIREUSER','1');
//if (! defined('NOREQUIREDB'))    define('NOREQUIREDB','1');
//if (! defined('NOREQUIRESOC'))   define('NOREQUIRESOC','1');
//if (! defined('NOREQUIRETRAN'))  define('NOREQUIRETRAN','1');
//if (! defined('NOCSRFCHECK'))    define('NOCSRFCHECK','1');			// Do not check anti CSRF attack test
//if (! defined('NOSTYLECHECK'))   define('NOSTYLECHECK','1');			// Do not check style html tag into posted data
//if (! defined('NOTOKENRENEWAL')) define('NOTOKENRENEWAL','1');		// Do not check anti POST attack test
//if (! defined('NOREQUIREMENU'))  define('NOREQUIREMENU','1');			// If there is no need to load and show top and left menu
//if (! defined('NOREQUIREHTML'))  define('NOREQUIREHTML','1');			// If we don't need to load the html.form.class.php
//if (! defined('NOREQUIREAJAX'))  define('NOREQUIREAJAX','1');
//if (! defined("NOLOGIN"))        define("NOLOGIN",'1');				// If this page is public (can be called outside logged session)

// Change this following line to use the correct relative path (../, ../../, etc)
$res=0;
if (! $res && file_exists("../../main.inc.php")) $res=@include '../../main.inc.php';					// to work if your module directory is into dolibarr root htdocs directory
if (! $res && file_exists("../../../main.inc.php")) $res=@include '../../../main.inc.php';			// to work if your module directory is into a subdir of root htdocs directory
if (! $res && file_exists("../../../dolibarr/htdocs/main.inc.php")) $res=@include '../../../dolibarr/htdocs/main.inc.php';     // Used on dev env only
if (! $res && file_exists("../../../../dolibarr/htdocs/main.inc.php")) $res=@include '../../../../dolibarr/htdocs/main.inc.php';   // Used on dev env only
if (! $res) die("Include of main fails");
// Change this following line to use the correct relative path from htdocs
include_once(DOL_DOCUMENT_ROOT.'/core/class/html.formcompany.class.php');
dol_include_once('/beerresaler/class/beers.class.php');



// Load traductions files requiredby by page
//$langs->load("beerresaler");
//$langs->load("other");

// Get parameters
$id			= GETPOST('id','int');
$action		= GETPOST('action','alpha');
$cancel     = GETPOST('cancel');
$backtopage = GETPOST('backtopage');
$myparam	= GETPOST('myparam','alpha');


$search_name=GETPOST('search_name','alpha');
$search_description=GETPOST('search_description','alpha');
$search_prix=GETPOST('search_prix','alpha');
$search_enStock=GETPOST('search_enStock','int');



if (empty($action) && empty($id) && empty($ref)) $action='view';
if (empty($id)) $id=1;

// Protection if external user
if ($user->societe_id > 0)
{
	//accessforbidden();
}
//$result = restrictedArea($user, 'beerresaler', $id);


$object = new Beers($db);

$extrafields = new ExtraFields($db);

// fetch optionals attributes and labels
$extralabels = $extrafields->fetch_name_optionals_label($object->table_element);

// Load object
include DOL_DOCUMENT_ROOT.'/core/actions_fetchobject.inc.php';  // Must be include, not include_once  // Must be include, not include_once. Include fetch and fetch_thirdparty but not fetch_optionals

// Initialize technical object to manage hooks of modules. Note that conf->hooks_modules contains array array
$hookmanager->initHooks(array('beers'));



/*******************************************************************
* ACTIONS
*
* Put here all code to do according to value of "action" parameter
********************************************************************/

$parameters=array();
$reshook=$hookmanager->executeHooks('doActions',$parameters,$object,$action);    // Note that $action and $object may have been modified by some hooks
if ($reshook < 0) setEventMessages($hookmanager->error, $hookmanager->errors, 'errors');

if (empty($reshook))
{
	if ($cancel) 
	{
		if ($action != 'addlink')
		{
			$urltogo=$backtopage?$backtopage:dol_buildpath('/beerresaler/beers_list.php',1);
			header("Location: ".$urltogo);
			exit;
		}		
		if ($id > 0 || ! empty($ref)) $ret = $object->fetch($id,$ref);
		$action='';
	}
	
	// Action to add record
	if ($action == 'add')
	{
		//echo "<br/>action add";
	
		
		if (GETPOST('cancel'))
		{
			$urltogo=$backtopage?$backtopage:dol_buildpath('/beerresaler/beers_list.php',1);
			header("Location: ".$urltogo);
			exit;
		}

		$error=0;

		/* object_prop_getpost_prop */
	
	
	//Debug
	
		//var_dump($object);
	/*
	echo "<br/> before getNextNumRef";
	echo "<br/> socid  : $socid";
	echo "<br/> userid : $userid";
	echo "<br/> soc  : $soc";
	echo "<br/> user : $user";
	
	*/
	//var_dump($object);

	//echo "<br/> _POST : <pre>" ; print_r($_POST) ; echo "</pre>";
	//echo "<br/> object : <pre>" ; print_r($object) ; echo "</pre>";
		
	 /**/
	 //echo "getnextnumref : ";  echo $object->getNextNumRef($userid);
	 //exit();
	
	if (!empty($socid)) {
		$thirdparty=new Societe($db);
		$thirdparty->fetch($socid);
		//$object->ref = $object->getNextNumRef($userid,$thirdparty);
	} else {
			//echo "<br/>in";
		
		//hack pour table perso (ne fonctionnera pas en full dolibarr)
		$object->ref = ' '; //$object->getNextNumRef($userid);
		//echo "<br/>" . $object->ref;
		
	}
	//echo "<br/>out";
	//echo "<br/> after getNextNumRef";
	
	
	$object->name=GETPOST('name','alpha');
	$object->description=GETPOST('description','alpha');
	$object->prix=GETPOST('prix','alpha');
	$object->enStock=GETPOST('enStock','int');

		
		
		if (empty($object->ref))
		{
			$error++;
			setEventMessages($langs->trans("ErrorFieldRequired",$langs->transnoentitiesnoconv("Ref")), null, 'errors');
		}
		
		
		
		if (! $error)
		{
			echo "test objet->create";
			$result=$object->create($user);
			echo "\$result <pre>"; print_r($result);echo"</pre>";
			if ($result > 0)
			{
				// Creation OK
				$urltogo=$backtopage?$backtopage:dol_buildpath('/beerresaler/beers_list.php',1);
				header("Location: ".$urltogo);
				exit;
			}
			{
				// Creation KO
				if (! empty($object->errors)) setEventMessages(null, $object->errors, 'errors');
				else  setEventMessages($object->error, null, 'errors');
				$action='create';
			}
		}
		else
		{
			$action='create';
		}
	}

	// Action to update record
	if ($action == 'update')
	{
		echo "<br/>action update detected";
		
		$error=0;

		
	$object->name=GETPOST('name','alpha');
	$object->description=GETPOST('description','alpha');
	$object->prix=GETPOST('prix','alpha');
	$object->enStock=GETPOST('enStock','int');

	$object->ref = ' ';

		if (empty($object->ref))
		{
			$error++;
			setEventMessages($langs->transnoentitiesnoconv("ErrorFieldRequired",$langs->transnoentitiesnoconv("Ref")), null, 'errors');
		}

		if (! $error)
		{
			$result=$object->update($user);
			if ($result > 0)
			{
				$action='view';
			}
			else
			{
				// Creation KO
				if (! empty($object->errors)) setEventMessages(null, $object->errors, 'errors');
				else setEventMessages($object->error, null, 'errors');
				$action='edit';
			}
		}
		else
		{
			$action='edit';
		}
	
	//felix: retour au listing apres modif
	header("Location: ".dol_buildpath('/beerresaler/beers_list.php',1));
			exit;
	}

	// Action to delete
	if ($action == 'confirm_delete')
	{
		
		$result=$object->delete($user);
		if ($result > 0)
		{
			// Delete OK
			echo "<br/> delete ok";
			setEventMessages("RecordDeleted", null, 'mesgs');
			header("Location: ".dol_buildpath('/beerresaler/beers_list.php',1));
			exit;
		}
		else
		{
			if (! empty($object->errors)) setEventMessages(null, $object->errors, 'errors');
			else setEventMessages($object->error, null, 'errors');
		}
	}
}




/***************************************************
* VIEW
*
* Put here all code to build page
****************************************************/

llxHeader('','Ajout au stock','');

$form=new Form($db);



// Put here content of your page

// Example : Adding jquery code
print '<script type="text/javascript" language="javascript">
jQuery(document).ready(function() {
	function init_myfunc()
	{
		jQuery("#myid").removeAttr(\'disabled\');
		jQuery("#myid").attr(\'disabled\',\'disabled\');
	}
	init_myfunc();
	jQuery("#mybutton").click(function() {
		init_myfunc();
	});
});
</script>';


// Part to create
if ($action == 'create')
{
	echo "action create";
	
	print load_fiche_titre($langs->trans("Ajouter nouvelle biere"));

	print '<form method="POST" action="'.$_SERVER["PHP_SELF"].'">';
	print '<input type="hidden" name="action" value="add">';
	print '<input type="hidden" name="backtopage" value="'.$backtopage.'">';

	dol_fiche_head();

	print '<table class="border centpercent">'."\n";
	// print '<tr><td class="fieldrequired">'.$langs->trans("Label").'</td><td><input class="flat" type="text" size="36" name="label" value="'.$label.'"></td></tr>';
	// 
/*
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldname").'</td><td><input class="flat" type="text" name="name" value="'.GETPOST('name').'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fielddescription").'</td><td><input class="flat" type="text" name="description" value="'.GETPOST('description').'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldprix").'</td><td><input class="flat" type="text" name="prix" value="'.GETPOST('prix').'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("FieldenStock").'</td><td><input class="flat" type="text" name="enStock" value="'.GETPOST('enStock').'"></td></tr>';
*/

print '<tr><td class="fieldrequired">'.Nom.'</td><td><input class="flat" type="text" name="name" value="'.GETPOST('name').'"></td></tr>';
print '<tr><td class="fieldrequired">'.Description.'</td><td><input class="flat" type="text" name="description" value="'.GETPOST('description').'"></td></tr>';
print '<tr><td class="fieldrequired">'.Prix.'</td><td><input class="flat" type="text" name="prix" value="'.GETPOST('prix').'"></td></tr>';
print '<tr><td class="fieldrequired">'.enStock.'</td><td><input class="flat" type="text" name="enStock" value="'.GETPOST('enStock').'"></td></tr>';


	print '</table>'."\n";

	dol_fiche_end();

	print '<div class="center"><input type="submit" class="button" name="add" value="'.$langs->trans("Create").'"> &nbsp; <input type="submit" class="button" name="cancel" value="'.$langs->trans("Cancel").'"></div>';

	print '</form>';
}


//if ( ! empty( GETPOST('delete','alpha') ) ) $action='confirm_delete';

// Part to edit record
if (($id || $ref) && $action == 'edit')
{
	echo "Edit mode";
	
	print load_fiche_titre('Edition Biere');//$langs->trans("MyModule"));
    
	print '<form method="POST" action="'.$_SERVER["PHP_SELF"].'">';
	print '<input id="myAction" type="hidden" name="action" value="update">';
	print '<input type="hidden" name="backtopage" value="'.$backtopage.'">';
	print '<input type="hidden" name="id" value="'.$object->id.'">';
	
	
	dol_fiche_head();

	print '<table class="border centpercent">'."\n";
	// print '<tr><td class="fieldrequired">'.$langs->trans("Label").'</td><td><input class="flat" type="text" size="36" name="label" value="'.$label.'"></td></tr>';
	// 
/*	
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldname").'</td><td><input class="flat" type="text" name="name" value="'.$object->name.'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fielddescription").'</td><td><input class="flat" type="text" name="description" value="'.$object->description.'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldprix").'</td><td><input class="flat" type="text" name="prix" value="'.$object->prix.'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("FieldenStock").'</td><td><input class="flat" type="text" name="enStock" value="'.$object->enStock.'"></td></tr>';
*/

print '<tr><td class="fieldrequired">'.$langs->trans("Name").'</td><td><input class="flat" type="text" name="name" value="'.$object->name.'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Description").'</td><td><input class="flat" type="text" name="description" value="'.$object->description.'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Prix").'</td><td><input class="flat" type="text" name="prix" value="'.$object->prix.'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("EnStock").'</td><td><input class="flat" type="text" name="enStock" value="'.$object->enStock.'"></td></tr>';

// <a href= dol_buildpath('/beerresaler/beers_list.php',1); ></a>

	print '</table>';
	
	dol_fiche_end();
	
	print '<script> function deleteAction() { console.log("myActionValue Before = " + $("#myAction").val() );$("#myAction").val("delete") ; console.log("myActionValue = " + $("#myAction").val() ); }</script>';

	print '<div class="center"><input type="submit" class="button" name="save" value="'.$langs->trans("Save").'">';
	
	print ' &nbsp; <div class="center"><input type="submit" class="button" name="delete" value="Supprimer" onClick="deleteAction();">';
	
	print ' &nbsp; <input type="submit" class="button" name="cancel" value="'.$langs->trans("Cancel").'">';
	
	print '</div>';
	
	print '</div>';

	print '</form>';
}



// Part to show record
if ($object->id > 0 && (empty($action) || ($action != 'edit' && $action != 'create')))
{
    $res = $object->fetch_optionals($object->id, $extralabels);

	//$head = commande_prepare_head($object);
	$head = '';
	dol_fiche_head($head, 'order', $langs->trans("CustomerOrder"), 0, 'order');
		
	print load_fiche_titre('Détail Biere');//$langs->trans("MyModule"));
    
	dol_fiche_head();

	if ($action == 'delete') {
		echo"</br> action delete detected";
		$formconfirm = $form->formconfirm($_SERVER["PHP_SELF"] . '?id=' . $object->id, $langs->trans('DeleteMyOjbect'), $langs->trans('ConfirmDeleteMyObject'), 'confirm_delete', '', 0, 1);
		print $formconfirm;
	}
	
	print '<table class="border centpercent">'."\n";
	// print '<tr><td class="fieldrequired">'.$langs->trans("Label").'</td><td>'.$object->label.'</td></tr>';
	// 
/*	
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldname").'</td><td>$object->name</td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fielddescription").'</td><td>$object->description</td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldprix").'</td><td>$object->prix</td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("FieldenStock").'</td><td>$object->enStock</td></tr>';
*/

// print_r($res);

print '<tr><td class="fieldrequired">'.Name.'</td><td>$object->name</td></tr>';
print '<tr><td class="fieldrequired">'.Description.'</td><td>$object->description</td></tr>';
print '<tr><td class="fieldrequired">'.Prix.'</td><td>$object->prix</td></tr>';
print '<tr><td class="fieldrequired">'.enStock.'</td><td>$object->enStock</td></tr>';

	print '</table>';
	
	dol_fiche_end();


	// Buttons
	print '<div class="tabsAction">'."\n";
	$parameters=array();
	$reshook=$hookmanager->executeHooks('addMoreActionsButtons',$parameters,$object,$action);    // Note that $action and $object may have been modified by hook
	if ($reshook < 0) setEventMessages($hookmanager->error, $hookmanager->errors, 'errors');

	if (empty($reshook))
	{
		if ($user->rights->beerresaler->write)
		{
			print '<div class="inline-block divButAction"><a class="butAction" href="'.$_SERVER["PHP_SELF"].'?id='.$object->id.'&amp;action=edit">'.$langs->trans("Modify").'</a></div>'."\n";
		}

		if ($user->rights->beerresaler->delete)
		{
			print '<div class="inline-block divButAction"><a class="butActionDelete" href="'.$_SERVER["PHP_SELF"].'?id='.$object->id.'&amp;action=delete">'.$langs->trans('Delete').'</a></div>'."\n";
		}
	}
	print '</div>'."\n";


	// Example 2 : Adding links to objects
	// Show links to link elements
	//$linktoelem = $form->showLinkToObjectBlock($object, null, array('beers'));
	//$somethingshown = $form->showLinkedObjectBlock($object, $linktoelem);

}


// End of page
llxFooter();
$db->close();
