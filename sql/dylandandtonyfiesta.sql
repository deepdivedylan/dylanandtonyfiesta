DROP TABLE IF EXISTS media;
DROP TABLE IF EXISTS message;
DROP TABLE IF EXISTS profile;

CREATE TABLE profile (
	profileId VARCHAR(18) NOT NULL,
	profileName VARCHAR(32) NOT NULL,
	profileService CHAR(1) NOT NULL,
	PRIMARY KEY(profileId)
);

CREATE TABLE message (
	messageId VARCHAR(18) NOT NULL,
	messageProfileId VARCHAR(18) NOT NULL,
	messageContent VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL,
	messageDateTime DATETIME(6) NOT NULL,
	FOREIGN KEY(messageProfileId) REFERENCES profile(profileId),
	PRIMARY KEY(messageId)
);

CREATE TABLE media (
	mediaId VARCHAR(18) NOT NULL,
	mediaMessageId VARCHAR(18) NOT NULL,
	mediaType VARCHAR(10) NOT NULL,
	mediaUrl VARCHAR(255) NOT NULL,
	FOREIGN KEY(mediaMessageId) REFERENCES message(messageId),
	PRIMARY KEY(mediaId)
);
