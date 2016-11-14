use cwinkebt_Messenger;

SELECT *
 from person
 where ((fname = :fname) && (lname = :lname) && (birthday = :birthday))
 LIMIT 1;
