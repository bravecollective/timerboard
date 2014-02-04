ALTER TABLE mapdenormalize
ADD INDEX `mapDenormalize_IX_constellation` (`constellationID`),
ADD INDEX `mapDenormalize_IX_groupConstellation` (`groupID`, `constellationID`),
ADD INDEX `mapDenormalize_IX_groupRegion` (`groupID`, `regionID`),
ADD INDEX `mapDenormalize_IX_groupSystem` (`groupID`, `solarSystemID`),
ADD INDEX `mapDenormalize_IX_orbit` (`orbitID`),
ADD INDEX `mapDenormalize_IX_region` (`regionID`),
ADD INDEX `mapDenormalize_IX_system` (`solarSystemID`),
ADD INDEX `itemName` (`itemName`),
ADD INDEX `mapDenormalize_security` (`security`),
ADD INDEX `mapDenormalize_typeID` (`typeID`);