

## The use of masks

The core process of URL parameter removal utilizes specific masks.

| Description                                  | Example mask                                             |
|----------------------------------------------|----------------------------------------------------------|
| exact match of one query key on any domain   | utm_campaign                                             |
| match of some keys on any domain             | utm_&#42;<br> &#42;tm_&#42;                              |
| exact match of one query key on one domain   | utm_campaign@test.net                                    |
| exact match of one query key on some domains | utm_campaign@test.&#42; <br>utm_campaign@&#42;test.&#42; |
| match of some keys on one domain             | utm_&#42;@test.net <br>&#42;x*@test.net                  |
| match of some keys on some domains           | utm_&#42;@test.&#42;   <br>&#42;x*@&#42;test.&#42;       |



Some examples are outlined in the table below.

| Mask       | URL 1<br> test.com/?a=1&b=2 | URL 2<br> test.net/?a=1&abb=2 | URL 3<br>  test2.com/?a=1&b=2 |
|------------|------------------------------|-------------------------------|-------------------------------|
| a          | test.com/?b=2                | test.net/?abb=2               | test2.com/?b=2                |                           |
| a*         | test.com/?b=2                | test.net/                     | test2.com/?b=2                |
| test.com@a | test.com/?b=2                | test.net/?a=1&abb=2           | test2.com/?a=1&b=2            |
| test.*@a   | test.com/?b=2                | test.net/?abb=2               | test2.com/?a=1&b=2            |

