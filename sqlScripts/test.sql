--INSERT INTO users (login,password) VALUES
--('pit','6789'),('nick','12345') ;
--SELECT * FROM users ;
--CREATE TRIGGER insert_user AFTER INSERT ON users
--  FOR EACH ROW
--  INSERT INTO userprofile (userid) VALUES (new.userId) ;
--insert into users (login,password) values ('nadin','6789') ;
INSERT INTO users (login,password) VALUES ("marinaNNNN__","12345"),
                                           ("piterMMMM__","12345") ;

SELECT users.login,
                gallery.galleryid,
                gallery.themeName AS galleryname,
                gallery.comment
                from gallery,users
                where gallery.userid = users.userid ;

SELECT fileimg, comment FROM galleryContent WHERE galleryid = "99" ;
Select * from gallery ;

SELECT * FROM galleryContent ;
SELECT * FROM users ;
SELECT * FROM userprofile ;