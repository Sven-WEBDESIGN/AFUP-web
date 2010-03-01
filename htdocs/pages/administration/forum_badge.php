<?php

require_once '../../include/prepend.inc.php';
// Gestion des droits
require_once 'Afup/AFUP_Utils.php';
$droits = AFUP_Utils::fabriqueDroits($bdd);

if (!$droits->estConnecte() ) {
   header('Location: index.php?page=connexion&echec=' . $droits->verifierEchecConnexion());
   exit;
}



require_once 'Afup/AFUP_Inscriptions_Forum.php';
require_once 'Afup/AFUP_Facturation_Forum.php';
require_once 'Afup/AFUP_Forum.php';

$forum = new AFUP_Forum($bdd);
$forum_inscriptions = new AFUP_Inscriptions_Forum($bdd);
$id_forum = 4;
$badges =  $forum_inscriptions->obtenirListePourBadges($id_forum);
$badge_prints =array();
$nb_cols = 3;
$nb_rows = 4;
$badge_row = 1;
$badge_col = 1;
$badge_page = 1;

foreach ($badges as $nb => $badge )
{

  preg_match('@\<tag\>(.*)\</tags\>@i', $badge['commentaires'], $matches);
  $tags =  isset($matches[1]) ? $matches[1] : '';
  $tags = explode(';',$tags);
  $tags = implode(' - ',array_filter($tags));
  $badge['tags'] = $tags;
  $lib_pass = $AFUP_Tarifs_Forum_Lib[$badge['type_inscription']];
  switch ($badge['type_inscription'])
  {
    case AFUP_FORUM_PREMIERE_JOURNEE:
      $lib_pass = 'PASS JOUR 1 (Jeudi)';
      break;
    case AFUP_FORUM_DEUXIEME_JOURNEE:
      $lib_pass = 'PASS JOUR 2 (Vendredi)';
      break;
    case AFUP_FORUM_2_JOURNEES:
    case AFUP_FORUM_2_JOURNEES_AFUP:
    case AFUP_FORUM_2_JOURNEES_ETUDIANT:
    case AFUP_FORUM_2_JOURNEES_PREVENTE:
    case AFUP_FORUM_2_JOURNEES_AFUP_PREVENTE:
    case AFUP_FORUM_2_JOURNEES_ETUDIANT_PREVENTE:
    case AFUP_FORUM_2_JOURNEES_COUPON:
    case AFUP_FORUM_INVITATION:
      $lib_pass = 'PASS 2 JOURS';
      break;
    case AFUP_FORUM_ORGANISATION:
    case AFUP_FORUM_PRESSE:
    case AFUP_FORUM_CONFERENCIER:
    case AFUP_FORUM_SPONSOR:
      $lib_pass = strtoupper($AFUP_Tarifs_Forum_Lib[$badge['type_inscription']]);
      break;

    default:
      ;
      break;
  }
  $badge['type_pass'] = $lib_pass;

  // var_dump($badge);die;
  $badge_prints[$badge_page][$badge_row][$badge_col]= $badge;


  if ($badge_col % $nb_cols == 0 )
  {
    $badge_col = 1;

    if ($badge_row % $nb_rows == 0 )
    {
      $badge_page++;
      $badge_row= 1;
    }
    else
    {
      $badge_row++;
    }
  }
  else
  {
    $badge_col++;
  }
}
// on remplit avec du vide les badges restants

//var_dump($badge_prints);die;

$last_page_badges = $badge_prints[$badge_page];
for ($empty_rows = 1; $empty_rows <= $nb_rows; $empty_rows++)
{
  for ($empty_cols = 1; $empty_cols <= $nb_cols; $empty_cols++)
  {
    //var_dump($empty_rows,$empty_cols,'') ;
    // on rajoute 2 pages de badges vide
    $badge_prints[$badge_page+1][$empty_rows][$empty_cols] = false;
    $badge_prints[$badge_page+2][$empty_rows][$empty_cols] = false;
    if (key_exists($empty_rows,$last_page_badges))
    {
      if (!key_exists($empty_cols,$last_page_badges[$empty_rows]))
      {
        $last_page_badges[$empty_rows][$empty_cols] = false;
      }
    }
    else
    {
      $last_page_badges[$empty_rows][1] = false;
    }
  }
}
$badge_prints[$badge_page]= $last_page_badges;

$programme = $forum->genAgenda('2009',true,true);

$code_salle[4]= "AM";
$code_salle[5]= "S1";
$code_salle[6]= "S4";
//var_dump( $programme);die;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
<style media="all">
td {
  width: 502px;
  height: 576px;
  border: 0px solid red;
  text-align: center;
  font-size: 30px;
  /*background-color: red;*/
}

.programme td {
  font-size: 10px;
}

table {
  width: 1850px;
  height: 2482px;
  page-break-after: always;
  padding: 50px;
  /*background-color: red;*/
}

th.coin {
  width: 70px;
  height: 20px;
  border: 0px solid blue;
  text-align: center;
  /*background-color: red;*/
}

th {
  font-size: 10px;
  /*background-color: red;*/
}
</style>
</head>

<body style="margin: 0; padding: 0px; ">
<?php foreach($badge_prints as $nb_page => $page): ?>
<?php if($nb_page == 1) :?>
<table border="0" cellpadding="0" cellspacing="0" align="center" class="programme">
  <tr>
    <th class="coin" valign="bottom">
    <div style="float: left" align="right"><br />
    __</div>
    <div style="float: right" align="right">|<br />
    <br />
    </div>

    </th>
    <th valign="top">
    <div style="float: right" align="right">|</div>
    </th>
    <th valign="top">
    <div style="float: right" align="right">|</div>
    </th>
    <th valign="top">
    <div style="float: right" align="right">|</div>
    </th>
    <th class="coin" valign="bottom">
    <div style="float: right" align="right"><br />
    __</div>
    </th>
  </tr>
  <?php foreach(array(1,2,3,4) as $row): ?>
  <tr>
    <th valign="bottom" align="left">__</th>
    <?php foreach(array(1,2,3) as $col): ?>
    <td>
    <div style="padding: 20px;"><?php foreach($programme as $date => $programme_jour): ?>
    <div style="font-size: 15px; font-weight: bold; padding: 5px;"><?php echo $date == '12-11-2009'?'Jeudi 12':'Vendredi 13';?>
    Novembre 2009</div>
    <?php foreach($programme_jour as $hour => $session_hours): ?> <?php $nb++;$session = $session_hours[0];?>
    <div style="padding: 3px;background-color:<?php echo $nb % 2 ==0?'white':'#E7E7E7'; ?>;">
    <div
      style="float: left; width: 70px; border: 0px solid; padding: 0px; text-align: center; vertical-align: middle;"><?php echo $hour;?></div>
    <div style="float: right;padding: 0px;border:0px solid;width: 410px;height:<?php echo $session['keynote']==1 ?'13':'40' ?>px;text-align: left;vertical-align: middle;" >
    <?php foreach($session_hours as $session): ?><span style="width: 50px;font-size: 9px;font-family: Lucida Sans Typewriter "><?php echo $code_salle[$session['id_salle']]?> -</span> <?php echo $session['titre']?><br />
    <?php endforeach; ?></div>
    <br style="clear: both;" />
    </div>
    <?php endforeach; ?> <?php endforeach; ?> <i>AM (Amphi) - S1 (Salle 1) - S4 (Salle 4)</i></div>
    </td>
    <?php endforeach;?>
    <th valign="bottom" align="right">__</th>
  </tr>
  <?php endforeach; ?>
  <tr>
    <th valign="bottom" class="coin">
    <div style="float: right" align="right">|</div>
    </th>
    <th valign="bottom">
    <div style="float: right" align="right">|</div>
    </th>
    <th valign="bottom">
    <div style="float: right" align="right">|</div>
    </th>
    <th valign="bottom">
    <div style="float: right" align="right">|</div>
    </th>
    <th valign="bottom" class="coin"></th>
  </tr>
</table>
  <?php endif;?>
<table border="0" cellpadding="0" cellspacing="0" align="center">
  <tr>
    <th class="coin" valign="bottom">
    <div style="float: left" align="right"><br />
    __</div>
    <div style="float: right" align="right">|<br />
    <br />
    </div>

    </th>
    <th valign="top">
    <div style="float: right" align="right">|</div>
    </th>
    <th valign="top">
    <div style="float: right" align="right">|</div>
    </th>
    <th valign="top">
    <div style="float: right" align="right">|</div>
    </th>
    <th class="coin" valign="bottom">
    <div style="float: right" align="right"><br />
    __</div>
    </th>
  </tr>
  <?php foreach($page as $row): ?>
  <tr>
    <th valign="bottom" align="left">__</th>
    <?php foreach($row as $badge): ?>
    <td valign="top">
    <img src="/templates/administration/images/bf09h.png" alt=""  style="padding-top:10px;"/>
    <div style="height:250px;vertical-align: middle;padding-top: 80px;">
    <?php if($badge) :?>

    <div style="font-size: 30px;padding: 10px;font-weight: bold;"><?php echo $badge['prenom']?> <?php echo $badge['nom']?></div>
    <div style="font-size: 25px;padding: 10px;"><?php echo $badge['societe']?></div>
    <div style="font-size: 18px;padding: 10px;"><?php echo $badge['type_pass']?></div>
    <div style="font-size: 16px;padding: 10px;font-weight: bold;"><?php echo $badge['tags']?> </div>
     <?php endif;?>
     </div>
    </td>

    <?php endforeach; ?>
    <th valign="bottom" align="right">__</th>
  </tr>
  <?php endforeach; ?>
  <tr>
    <th valign="bottom" class="coin">
    <div style="float: right" align="right">|</div>
    </th>
    <th valign="bottom">
    <div style="float: right" align="right">|</div>
    </th>
    <th valign="bottom">
    <div style="float: right" align="right">|</div>
    </th>
    <th valign="bottom">
    <div style="float: right" align="right">|</div>
    </th>
    <th class="coin"></th>
  </tr>
</table>
  <?php endforeach; ?>
</body>
</html>
