use cwinkebt_Messenger;

select * 
from (person inner join poeple_group on person.person_id = poeple_group.person_id) join groups on groups.groups_id = poeple_group.groups_id
where name = :name
order by posted;
