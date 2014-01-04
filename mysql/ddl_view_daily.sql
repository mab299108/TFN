USE dataLogger;

CREATE or REPLACE VIEW `daily`
AS SELECT
   avg(`tempData`.`temp`) AS `temp`,
   `tempData`.`sensor` AS `sensor`,
   month(`tempData`.`time`) AS `month`,
   dayofmonth(`tempData`.`time`) AS `day`,
   hour(`tempData`.`time`) AS `hour`
FROM `tempData` 
WHERE (`tempData`.`time` > (now() - interval 2 day)) 
GROUP BY
	`tempData`.`sensor`, 
	month(`tempData`.`time`),
	dayofmonth(`tempData`.`time`),
	hour(`tempData`.`time`);