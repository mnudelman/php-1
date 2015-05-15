SELECT users.login,
                 gallery.galleryid,
                 gallery.themeName AS galleryname,
                 gallery.comment
                 from gallery,users
                 where gallery.userid = users.userid
                 AND gallery.userid in (SELECT userid from users where login = 'mnudelman' )
                  order by users.login ;