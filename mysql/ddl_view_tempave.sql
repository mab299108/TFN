CREATE or REPLACE VIEW `tempave`
AS SELECT
   avg(`tempdata`.`temp`) AS `temp`,
   `tempdata`.`sensor` AS `sensor`,
   month(`tempdata`.`time`) AS `month`,
   dayofmonth(`tempdata`.`time`) AS `day`,
   hour(`tempdata`.`time`) AS `hour`
FROM `tempdata` 
WHERE (`tempdata`.`time` > (now() - interval 30 day)) 
GROUP BY
	`tempdata`.`sensor`, 
	month(`tempdata`.`time`),
	dayofmonth(`tempdata`.`time`),
	hour(`tempdata`.`time`);