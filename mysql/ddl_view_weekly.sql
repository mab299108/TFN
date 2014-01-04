USE dataLogger;

CREATE or REPLACE VIEW `weekly`
AS SELECT
   avg(`tempData`.`temp`) AS `temp`,
   `tempData`.`sensor` AS `sensor`,
   month(`tempData`.`time`) AS `month`,
   dayofmonth(`tempData`.`time`) AS `day`
FROM `tempData` 
WHERE (`tempData`.`time` > (now() - interval 30 day)) 
GROUP BY
	`tempData`.`sensor`, 
	month(`tempData`.`time`),
	dayofmonth(`tempData`.`time`);