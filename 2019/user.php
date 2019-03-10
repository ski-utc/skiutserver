<?php

function getPriceFromLogin($db,$login)
{
	$user = $db->prepare('
		SELECT *
		FROM `users_2019`
		WHERE `login`=:login
	');
	$user->bindParam(':login',$login,PDO::PARAM_STR);
	$user->execute();
	$user = $user->fetch();
	$price = 320;  // On ne s'occupe pas des tremplins


	// switch($user['type'])
	// {
	// 	case 0: $base = 290; break;
	// 	case 1: $base = 310; break;
	// 	case 2: $base = 320; break;
	// }
  //
	// return ($base + calcPrice($user));


  if($user['food'] < 2){
      $price += 42;
  }
  if($user['assurance_annulation'] > 0){
      $price += 20;
  }
  if($user['assurance_rapa'] > 0){
      $price += 7;
  }
  switch($user['equipment'])
  {
    case 0: // Aucun
      break;
    case 1: // Skis
      switch($user['pack'])
      {
        case 1: // Bronze
          switch($user['items'])
          {
            case 0:
              $price += 15.75;
              break; //chaussures seuls
            case 1:
              $price += 29.25;
              break; // ski seuls
            case 3:
              $price += 46;
              break; // full pack
          }
          break;
        case 2: // Argent
          switch($user['items'])
          {
            case 0:
              $price += 22.75;
              break;
            case 1:
              $price += 42.25;
              break;
            case 3:
              $price += 65;
              break;
          }
          break;
        case 3: // Or
          switch($user['items'])
          {
            case 0:
              $price += 28;
              break;
            case 1:
              $price += 52;
              break;
            case 3:
              $price += 80;
              break;
          }
          break;
      }
      break;
    case 2: // Snowboard
      switch($user['pack'])
      {
        case 0: break;
        case 1: break; // Pas de pack Bronze pour le snow
        case 2: // Argent
          switch($user['items'])
          {
            case 0:
              $price += 22.75;
              break;
            case 2:
              $price += 42.25;
              break;
            case 4:
              $price += 65;
              break;
          }
          break;
        case 3: // Or
          switch($user['items'])
          {
            case 0:
              $price += 28;
              break;
            case 2:
              $price += 52;
              break;
            case 4:
              $price += 80;
              break;
          }
          break;
      }
      break;
  }

  return $price;
}


function connectAsAdmin($user,$password)
{
	if($user == "perm")
		if($password == "croziflette")
		{
			$_SESSION['admin'] = "perm";
			return true;
		}

	return false;
}

function getEtuFromLogin($db,$login)
{
	$etu = $db->prepare('
				SELECT *
				FROM `users_2019`
				WHERE `login`=:login
				');
	$etu->bindParam(':login',$login,PDO::PARAM_STR);
	$etu->execute();
	$etu = $etu->fetch();
	return $etu;
}
// function getCours($db,$login,$anim)
// {
// 	$etu = $db->prepare('
// 				SELECT count(*) as nb,id_anim
// 				FROM `anim_user`
// 				WHERE `login`=:login and id_anim = :anim group by id_anim
// 				');
// 	$etu->bindParam(':login',$login,PDO::PARAM_STR);
// 	$etu->bindParam(':anim',$anim,PDO::PARAM_INT);
// 	$etu->execute();
// 	$etu = $etu->fetch();
// 	return $etu;
// }

function getCours($db,$user,$anim)
{
	$login = strpos($user, "@") ? substr($user, 0, strpos($user, "@")) : $user;
	$etu = $db->prepare('
				SELECT sum(quantity) as nb,id_option
				FROM `rel_options_users`
				WHERE `id_user`=:user and id_option = :anim group by id_option
				');
	$etu->bindParam(':user',$login,PDO::PARAM_STR);
	$etu->bindParam(':anim',$anim,PDO::PARAM_INT);
	$etu->execute();
	$etu = $etu->fetch();
	return $etu;
}

function inHorsPiste($db,$login)
{
	$etu = $db->prepare('
				SELECT count(*) as nb,login
				FROM `hors_piste_2017`
				WHERE `login`=:login group by login
				');
	$etu->bindParam(':login',$login,PDO::PARAM_STR);
	$etu->execute();
	$etu = $etu->fetch();
	return $etu;
}
function inCours($db,$login)
{
	$etu = $db->prepare('
				SELECT count(*) as nb,login
				FROM `cours_2017`
				WHERE `login`=:login group by login
				');
	$etu->bindParam(':login',$login,PDO::PARAM_STR);
	$etu->execute();
	$etu = $etu->fetch();
	return $etu;
}

function getCoursSki($db,$login)
{
	$etu = $db->prepare('
				SELECT count(*) as nb,id_anim
				FROM `anim_user`
				WHERE `login`=:login and id_anim = 1 group by id_anim
				');
	$etu->bindParam(':login',$login,PDO::PARAM_STR);
	$etu->execute();
	$etu = $etu->fetch();
	return $etu;
}
function getCoursParapente($db,$login)
{
	$etu = $db->prepare('
				SELECT count(*) as nb
				FROM `anim_user`
				WHERE `login`=:login and (id_anim = 4) and tra_status = "V"
				');
	$etu->bindParam(':login',$login,PDO::PARAM_STR);
	$etu->execute();
	$etu = $etu->fetch();
	return $etu;
}
function getCoursAltitude($db,$login)
{
	$etu = $db->prepare('
				SELECT count(*) as nb,id_anim
				FROM `anim_user`
				WHERE `login`=:login and id_anim = 3 group by id_anim
				');
	$etu->bindParam(':login',$login,PDO::PARAM_STR);
	$etu->execute();
	$etu = $etu->fetch();
	return $etu;
}
function getCoursAeroplane($db,$login)
{
	$etu = $db->prepare('
				SELECT count(*) as nb
				FROM `anim_user`
				WHERE `login`=:login and (id_anim = 5 or id_anim = 6) and tra_status = "V"
				');
	$etu->bindParam(':login',$login,PDO::PARAM_STR);
	$etu->execute();
	$etu = $etu->fetch();
	return $etu;
}
function getCoursTraineau($db,$login)
{
	$etu = $db->prepare('
				SELECT count(*) as nb
				FROM `anim_user`
				WHERE `login`=:login and (id_anim = 7 or id_anim = 8) and tra_status = "V"
				');
	$etu->bindParam(':login',$login,PDO::PARAM_STR);
	$etu->execute();
	$etu = $etu->fetch();
	return $etu;
}
function getCoursMotoneige($db,$login)
{
	$etu = $db->prepare('
				SELECT count(*) as nb
				FROM `anim_user`
				WHERE `login`=:login and (id_anim = 9 or id_anim = 10) and tra_status = "V"
				');
	$etu->bindParam(':login',$login,PDO::PARAM_STR);
	$etu->execute();
	$etu = $etu->fetch();
	return $etu;
}
function getCoursHorsPiste($db,$login)
{
	$etu = $db->prepare('
				SELECT count(*) as nb
				FROM `anim_user`
				WHERE `login`=:login and (id_anim = 2)
				');
	$etu->bindParam(':login',$login,PDO::PARAM_STR);
	$etu->execute();
	$etu = $etu->fetch();
	return $etu;
}
function getCoursLaser($db,$login)
{
	$etu = $db->prepare('
				SELECT *
				FROM `anim_user`
				WHERE `login`=:login and (id_anim = 12)
				');
	$etu->bindParam(':login',$login,PDO::PARAM_STR);
	$etu->execute();
	$etu = $etu->fetch();
	return $etu;
}
function getExtFromLogin($db,$email)
{
	$ext = $db->prepare('
		SELECT *
		FROM `users_2019`
		WHERE `login`=:login AND `type`=2
		');
	$ext->bindParam(':login',$email,PDO::PARAM_STR);
	$ext->execute();
	$ext = $ext->fetch();
	return $ext;
}


function getTremplinFromLogin($db,$email)
{
	$tremplin = $db->prepare('
		SELECT *
		FROM `users_2019`
		WHERE `login`=:email AND `type`=1
		');
	$tremplin->bindParam(':email',$email,PDO::PARAM_STR);
	$tremplin->execute();
	$tremplin = $tremplin->fetch();
	return $tremplin;
}

function isService($name) {
    if(!isset($_SESSION['services']))
        return false;
    $services = $_SESSION['services'];
    return in_array($name,$services);
}
