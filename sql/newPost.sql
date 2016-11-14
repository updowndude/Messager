use cwinkebt_Messenger;

SELECT person.person_id as "pID", groups.groups_id as "gID"
from (person inner join poeple_group on person.person_id = poeple_group.person_id) join groups on groups.groups_id = poeple_group.groups_id
where ((fname = :fname) && (lname = :lname) && (birthday = :birthday) && (name = :name))

INSERT INTO poeple_group (groups_id, person_id, message, posted)
VALUES (:groups_id, :person_id, :message, :posted)
