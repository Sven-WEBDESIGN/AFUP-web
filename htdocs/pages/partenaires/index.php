<?php
require_once dirname(__FILE__) .'/../../../sources/Afup/Bootstrap/Http.php';

require_once 'Afup/AFUP_Partenariat.php';
require_once 'Afup/AFUP_Logs.php';

AFUP_Logs::initialiser($bdd, 0);

$partenariat = new AFUP_Partenariat($bdd);

$formulaire = &instancierFormulaire();

$formulaire->addElement('header'  , ''  , 'Vérifier l\'existance d\'un membre');
$formulaire->addElement('text', 'nom', 'Nom');
$formulaire->addElement('text', 'prenom', 'Prénom');
$formulaire->addElement('text', 'email', 'Email');
$formulaire->addElement('submit'  , 'verifier' , 'Vérifier');

if ($formulaire->validate()) {
	AFUP_Logs::log('Vérification par un partenaire de : '.$formulaire->exportValue('nom').' - '.$formulaire->exportValue('prenom').' - '.$formulaire->exportValue('email'));
	$smarty->assign(
		'resultat',
		$partenariat->verifierMembre(
			$formulaire->exportValue('nom'),
			$formulaire->exportValue('prenom'),
			$formulaire->exportValue('email')
		)
	);
}

$smarty->assign('formulaire', genererFormulaire($formulaire));
$smarty->display('membre.html');
