#uwwtd queries
- Francisca S. Braga (fsb@skanderborgforsyning.dk)
- Lluís Bosch (lbosch@icra.cat)
- Lluís Corominas (lcorominas@icra.cat)

Simple PHP website and UWWTD sqlite database that interact via queries:

The user can click each query and see the resulting table.

What the queries look for:

Small WWTPS &lt; 5000 PE
Life span of the WWTPs around 30 years
Life span equipment WWTPS around 20 years

1. The WWTPs that were built in the 70s,80s and 90s that are still activated
(no closing date) with the country code – so we can check how many WWTPS needs
renovations. From that WWTPs, the PE of them to check if they are small of not
(assuming that they will need to build, or reconstruction or redirected them).

So for this case: uwwState(1)-repCode-uwwHistoire-uwwInformation-uwwbeginLife
(1970s; 1980s, 1990s)-uwwCapacity &lt; 5000 PE.

2. Other case: Give me the WWTPs that have a higher uwwloadentering UWWTP than the
unwwcapacity (we can assume that these ones need renovations as well, even if
they were build in the past years).

So for this case: uwwState(1)-repCode-uwwHistoire-uwwInformation- uwwLoadEntering> uwwLoadCapacity
