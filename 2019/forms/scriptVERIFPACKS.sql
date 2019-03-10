SELECT * FROM `users_2019` WHERE `equipment`=1 AND `pack`=2 AND `items`=2;
SELECT * FROM `users_2019` WHERE `equipment`=1 AND `pack`=1 AND `items`=2;
SELECT * FROM `users_2019` WHERE `equipment`=1 AND `pack`=1 AND `items`=4;
SELECT * FROM `users_2019` WHERE `equipment`=1 AND `pack`=2 AND `items`=4;
SELECT * FROM `users_2019` WHERE `equipment`=1 AND `pack`=3 AND `items`=2;
SELECT * FROM `users_2019` WHERE `equipment`=1 AND `pack`=3 AND `items`=4;

SELECT * FROM `users_2019` WHERE `equipment`=2 AND `pack`=2 AND `items`=1;
SELECT * FROM `users_2019` WHERE `equipment`=2 AND `pack`=2 AND `items`=3;
SELECT * FROM `users_2019` WHERE `equipment`=2 AND `pack`=3 AND `items`=1;
SELECT * FROM `users_2019` WHERE `equipment`=2 AND `pack`=3 AND `items`=3;


Avoir les gens qui ont payé par chèque :
SELECT `login`,`firstName`,`lastName`,`email`,`price`,`cheq2`,`equipment`,`pack`,`items`,`assurance_annulation`,`assurance_rapa` FROM `users_2019` WHERE (`payment-type`=1 OR `payment-type`=2) AND `tra_id` IS NULL AND `payment-first-received` = 1
