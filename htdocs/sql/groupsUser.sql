use cwinkebt_Messenger;

select fname, lname
from (person inner join poeple_group on person.person_id = poeple_group.person_id) join groups on groups.groups_id = poeple_group.groups_id
where name = :name
GROUP BY person.person_id
order by posted
