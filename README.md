# margarita4
GTFS timetable viewer

## prohlížeč jízdních řádů

aneb [má-li to Seznam](https://www.novinky.cz/internet-a-pc/444975-seznam-cz-spustil-vlastni-jizdni-rady.html), proč bych to nemohl [mít i já](http://jizdnirady.svita.cz/pid)?

V dávných dobách v roce 2010 jsem se na fóru K-reportu účastnil jakési hry, kdy jsem jakože provozoval [svůj vlastní dopravní podnik](http://vdp-zaprazi.vesele.info). No a dopravní podnik potřebuje [jízdní řády](http://vdp-zaprazi.vesele.info/rubriky/pro-cestujici/jizdni-rady) a ručně je psát je pěkná otrava. Tak jsem si na to napsal vlastní program (zvaný VDP Margarita), který pro daný účel [celkem fungoval](http://strajt9.sweb.cz/margarita/). 

Postupem času jsem se k tomuto tématu vracel a zkoušel jsem program vylepšovat nebo brát z jiného konce. Dozvěděl jsem se o specifikaci [formátu GTFS](https://en.wikipedia.org/wiki/General_Transit_Feed_Specification) - de fakto se jedná o standard pro výměnu dat jízdních řádů. Zároveň se Seznam.cz [pustil do boje](https://www.lupa.cz/clanky/tohle-je-vysmech-uplne-uvolneni-dat-o-jizdnich-radech-chapsem-se-zas-nekona/) s CHAPSem, výsledkem čehož bylo, že jsou dnes jízdní řády českých dopravců už [v poměrně čitelné podobě](https://www.chaps.cz/cs/products/CIS). K dispozici je též [GTFS dataset](http://opendata.praha.eu/dataset/dpp-jizdni-rady), který zveřejňuje DPP (ten používám já).

## demo

- [Pražská integrovaná doprava](http://jizdnirady.svita.cz/pid?pk_campaign=github)
