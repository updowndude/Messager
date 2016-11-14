use cwinkebt_Messenger;

select CONCAT(person.fname,' ', person.lname, ' ',person.birthday) as fullPerson, poeple_group.message, poeple_group.posted
from (person inner join poeple_group on person.person_id = poeple_group.person_id)
  join groups on groups.groups_id = poeple_group.groups_id
where ((poeple_group.message IS NOT NULL) && (poeple_group.posted IS NOT NULL))
