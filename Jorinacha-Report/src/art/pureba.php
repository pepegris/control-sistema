<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
<table class="table table-dark table-striped" id="tblData">
<?php

$chontel = array ('6013216206738'	,
'6013216206938'	,
'6013216207938'	,
'6013216306938'	,
'6013216307938'	,
'6013216313138'	,
'6013216323938'	,
'6013216403438'	,
'6013216405838'	,
'6013216407938'	,
'6013216506738'	,
'6013216506938'	,
'6013216507938'	,
'6013216603538'	,
'6013216606938'	,
'6013216607938'	,
'6013216706738'	,
'6013216706938'	,
'6013216707938'	,
'6013216801738'	,
'6013216807938'	,
'6013216813138'	,
'6013219202338'	,
'6013219206938'	,
'6013219207938'	,
'6013219213138'	,
'6013219301738'	,
'6013219306938'	,
'6013219307938'	,
'6013219401738'	,
'6013219402338'	,
'6013219406938'	,
'6013219407938'	,
'6013219501738'	,
'6013219505838'	,
'6013219506938'	,
'6013219507938'	,
'6013219601738'	,
'6013219602338'	,
'6013219606938'	,
'6013219607938'	,
'6013219702338'	,
'6013219706938'	,
'6013219707938'	,
'6013219713138'	,
'6013219802338'	,
'6013219806938'	,
'6013219807938'	,
'6013219813138'	,
'6013219901738'	,
'6013219902338'	,
'6013219906938'	,
'6013219907938'	,
'6013220002338'	,
'6013220005138'	,
'6013220006938'	,
'6013220007938'	,
'6013220102338'	,
'6013220106938'	,
'6013220107938'	,
'6013220109538'	,
'6013220202328'	,
'6013220207928'	,
'6013220213128'	,
'6013220302303'	,
'6013220307903'	,
'6013220308903'	,
'6013220309803'	,
'6013272305172'	,
'6013272306972'	,
'6013272307472'	,
'6013272307972'	,
'6013272406972'	,
'6013272407972'	,
'6013272409572'	,
'6013272501772'	,
'6013272505872'	,
'6013272506972'	,
'6013272507972'	,
'6013272601738'	,
'6013272605838'	,
'6013272606938'	,
'6013272607938'	,
'6013272705138'	,
'6013272706938'	,
'6013272707438'	,
'6013272707938'	,
'6013272709538'	,
'6013272711238'	,
'6013272806938'	,
'6013272807438'	,
'6013272807938'	,
'6013272809538'	,
'6013272811238'	,
'6013272902338'	,
'6013272906938'	,
'6013272907938'	,
'6013273002372'	,
'6013273006972'	,
'6013273007972'	,
'6013273102338'	,
'6013273106938'	,
'6013273107938'	,
'6013273206938'	,
'6013273207938'	,
'6013273211238'	,
'6013276202338'	,
'6013276207438'	,
'6013276207938'	,
'6013276211238'	,
'6013276305138'	,
'6013276306938'	,
'6013276307438'	,
'6013276307938'	,
'6013276401738'	,
'6013276402338'	,
'6013276403438'	,
'6013276407938'	,
'6013276502338'	,
'6013276503438'	,
'6013276507938'	,
'60132765VIO38'	,
'6013276601738'	,
'6013276602338'	,
'6013276606938'	,
'6013276607938'	,
'6013276631538'	,
'6013276701738'	,
'6013276702338'	,
'6013276706938'	,
'6013276707938'	,
'6013276801725'	,
'6013276803425'	,
'6013276807925'	,
'6013276902325'	,
'6013276903425'	,
'6013276907925'	,
'6013277001725'	,
'6013277003425'	,
'6013277007925'	,
'6013277102325'	,
'6013277106925'	,
'6013277107925'	,
'6013277201725'	,
'6013277206925'	,
'6013277207925'	,
'6013277305125'	,
'6013277307925'	,
'6013277310425'	,
'6013277407425'	,
'6013277407925'	,
'6013277410425'	,
'6013280901725'	,
'6013280903425'	,
'6013280905825'	,
'6013280907925'	,
'6013281005125'	,
'6013281005825'	,
'6013281007625'	,
'6013281011225'	,
'6013281101725'	,
'6013281105825'	,
'6013281107425'	,
'6013281107925'	,
'6013281201725'	,
'6013281205825'	,
'6013281207425'	,
'6013281207925'	,
'6013281301725'	,
'6013281301740'	,
'6013281302325'	,
'6013281302340'	,
'6013281307925'	,
'6013281307940'	,
'6013281313125'	,
'6013281313140'	,
'6013291301725'	,
'6013291305125'	,
'6013291305825'	,
'6013291306925'	,
'6013291307925'	,
'6013291401725'	,
'6013291402325'	,
'6013291407925'	,
'6013291410825'	,
'6013291502325'	,
'6013291505825'	,
'6013291507925'	,
'6013291513125'	,
'6013291601725'	,
'6013291606925'	,
'6013291607425'	,
'6013291607925'	,
'6013291702303'	,
'6013291702306'	,
'6013291702325'	,
'6013291705103'	,
'6013291705106'	,
'6013291705125'	,
'6013291707903'	,
'6013291707906'	,
'6013291707925'	,
'6013291710403'	,
'6013291710406'	,
'6013291710425'	,
'6013291801725'	,
'6013291805825'	,
'6013291807925'	,
'6013291809525'	,
'6013291810425'	,
'6013291902325'	,
'6013291907425'	,
'6013291907925'	,
'6013291909525'	,
'6013291910425'	,
'6013292002325'	,
'6013292007925'	,
'6013292009525'	,
'6013292012025'	,
'6013292102325'	,
'6013292106925'	,
'6013292107925'	,
'6013292201725'	,
'6013292206925'	,
'6013292207925'	,
'6013292302325'	,
'6013292305125'	,
'6013292307425'	,
'6013292307925'	,
'6013292310425'	,
'6013292313125'	,
'6013292402303'	,
'6013292402306'	,
'6013292402325'	,
'6013292406925'	,
'6013292407425'	,
'6013292407903'	,
'6013292407906'	,
'6013292407925'	,
'6013292409503'	,
'6013292409506'	,
'6013292409525'	,
'6013292410403'	,
'6013292410406'	,
'6013292410425'	,
'6013292502325'	,
'6013292507925'	,
'6013292509525'	,
'6013292510425'	,
'6013292601725'	,
'6013292602325'	,
'6013292607925'	,
'6013292609525'	,
'6013292610425'	,
'6013292700225'	,
'6013292702325'	,
'6013292705125'	,
'6013292705825'	,
'6013292707425'	,
'6013292707925'	,
'6013292710425'	,
'6013292800225'	,
'6013292802325'	,
'6013292805125'	,
'6013292805825'	,
'6013292807425'	,
'6013292807925'	,
'6013292810425'	,
'6013292813125'	,
'60132928VIO25'	,
'6013307901788'	,
'6013307906988'	,
'6013307907988'	,
'6013307909588'	,
'6013308102388'	,
'6013308105188'	,
'6013308107988'	,
'6013308110488'	,
'6013308202388'	,
'6013308206788'	,
'6013308206988'	,
'6013308207988'	,
'6013308301788'	,
'6013308306988'	,
'6013308307988'	,
'6013308309588'	,
'6013308401788'	,
'6013308406988'	,
'6013308407988'	,
'6013308411288'	,
'6013308501788'	,
'6013308506988'	,
'6013308507988'	,
'6013308511288'	,
'6013310006938'	,
'6013310007938'	,
'6013310009538'	,
'6013310106938'	,
'6013310107938'	,
'6013310111238'	,
'6013310201738'	,
'6013310203438'	,
'6013310207938'	,
'6013310301738'	,
'6013310305138'	,
'6013310306938'	,
'6013310307938'	,
'6013310400238'	,
'6013310403438'	,
'6013310407938'	,
'6013310411238'	,
'6013313201725'	,
'6013313206925'	,
'6013313207925'	,
'6013313209525'	,
'6013313211225'	,
'6013313305125'	,
'6013313306925'	,
'6013313307425'	,
'6013313307925'	,
'6013313401725'	,
'6013313406925'	,
'6013313407925'	,
'6013313409525'	,
'6013313502325'	,
'6013313507925'	,
'6013313509525'	,
'6013313511225'	,
'6013313601725'	,
'6013313605825'	,
'6013313607925'	,
'6013313705125'	,
'6013313707425'	,
'6013313707925'	,
'6013313711225'	,
'6013313712225'	,
'6013313805825'	,
'6013313806925'	,
'6013313807925'	,
'6013313811225'	,
'6013313905125'	,
'6013313906925'	,
'6013313907925'	,
'6013313911225'	,
'60133139A4525'	,
'6013314002325'	,
'6013314005125'	,
'6013314007925'	,
'6013314011225'	,
'6013314101725'	,
'6013314102325'	,
'6013314105125'	,
'6013314106925'	,
'6013314107925'	,
'6013314201725'	,
'6013314207925'	,
'6013314209525'	,
'6013314210425'	,
'6013314301725'	,
'6013314307925'	,
'6013314309525'	,
'6013314310425'	,
'6013314401725'	,
'6013314407425'	,
'6013314407925'	,
'6013314409525'	,
'6013314501725'	,
'6013314506725'	,
'6013314507925'	,
'6013314509525'	,
'6013314602325'	,
'6013314606725'	,
'6013314606925'	,
'6013314607925'	,
'6013314702325'	,
'6013314706925'	,
'6013314707925'	,
'6013314713125'	,
'6013314806925'	,
'6013314807925'	,
'6013314813125'	,
'6013314906925'	,
'6013314907925'	,
'6013314913125'	,
'6013315001725'	,
'6013315006925'	,
'6013315007925'	,
'6013315009525'	,
'6013315011225'	,
'6013315105125'	,
'6013315105825'	,
'6013315107925'	,
'6013315111225'	,
'6013315201725'	,
'6013315206925'	,
'6013315207925'	,
'6013315213125'	,
'6013315305125'	,
'6013315306925'	,
'6013315307425'	,
'6013315307925'	,
'6013315406925'	,
'6013315407925'	,
'6013315410825'	,
'6013315413125'	,
'6013321303488'	,
'6013321307988'	,
'6013321311288'	,
'6013321403488'	,
'6013321405188'	,
'6013321407988'	,
'6013321411288'	,
'6013321501788'	,
'6013321507688'	,
'6013321507988'	,
'6013321511288'	,
'6013321607688'	,
'6013321607988'	,
'6013321609588'	,
'60133216VIO88'	,
'6013321706988'	,
'6013321707988'	,
'6013321711288'	,
'6013321805188'	,
'6013321807688'	,
'6013321807988'	,
'6013321811288'	,
'6013359407925'	,
'6013359507925'	,
'6013359607925'	,
'6013359707925'	,
'6013359807938'	,
'6013359907925'	,
'6013360007938'	,
'6013360009538'	,
'6013360096838'	,
'6013360107938'	,
'6013360202338'	,
'6013360206838'	,
'6013360207938'	,
'60133602VIO38'	,
'6013360301738'	,
'6013360307938'	,
'6013360396838'	,
'6013360400938'	,
'6013360446638'	,
'6013360490338'	,
'60133604B7438'	,
'60133604F8838'	,
'6013510303538'	,
'6013510305138'	,
'6013510307938'	,
'6013510407938'	,
'6013510408938'	,
'6013510412038'	,
'6013510505838'	,
'6013510507938'	,
'6013510607938'	,
'6013510629938'	,
'6013510703538'	,
'6013510705138'	,
'6013510707438'	,
'6013510707938'	,
'6013510711238'	,
'6013510829938'	,
'6013510907938'	,
'6013510929938'	,
'6013511001738'	,
'6013511005838'	,
'6013511006938'	,
'6013511007938'	,
'6013511107938'	,
'6013511108938'	,
'6013511112038'	,
'6013511207938'	,
'6013511208938'	,
'6013511212038'	,
'6013511302338'	,
'6013511307938'	,
'6013511311238'	,
'6013511403438'	,
'6013511407938'	,
'6013511411238'	,
'6013511507938'	,
'6013511511238'	,
'6013511607938'	,
'6013511611238'	,
'6013511705838'	,
'6013511707938'	,
'6013511807938'	,
'60135118C6938'	,
'60135118F0838'	,
'6013511916738'	,
'6013511974638'	,
'60135119A1438'	,
'60135119C8938'	,
'6013512002338'	,
'6013512003438'	,
'6013512007938'	,
'60135121C7038'	,
'60135121C7138'	,
'60135121C7238'	,
'6013512202338'	,
'6013512207938'	,
'6013512306938'	,
'6013512307438'	,
'6013512307938'	,
'6013512310838'	,
'6013512329938'	,
'6013512407938'	,
'6013512408938'	,
'6013512412038'	,
'6013512505838'	,
'6013512507938'	,
'6013512607938'	,
'6013512611238'	,
'6013512629938'	,
'6013512707938'	,
'6013512729938'	,
'6013512807938'	,
'6013512829938'	,
'6013512907938'	,
'6013512908938'	,
'6013513005138'	,
'6013513005838'	,
'6013513007938'	,
'6013513011238'	,
'6013513107938'	,
'6013513108938'	,
'60135131C7038'	,
'60135131C7338'	,
'60135131C7438'	,
'6013513208938'	,
'6013513212038'	,
'6013513301738'	,
'6013513305838'	,
'6013513308338'	,
'6013513399038'	,
'60135133C2138'	,
'6013513407938'	,
'6013513408938'	,
'6013513412038'	,
'6013513420638'	,
'6013513499038'	,
'6013513503438'	,
'6013513503538'	,
'6013513507938'	,
'6013513607938'	,
'6013513608938'	,
'6013513612038'	,
'6013513703338'	,
'6013513707938'	,
'6013513708938'	,
'6013513709838'	,
'6013513712038'	,
'6013513807938'	,
'6013513808938'	,
'6013513812038'	,
'6013513829938'	,
'6013513902338'	,
'6013513907938'	,
'6013514009838'	,
'6013514029938'	,
'60135140VIO38'	,
'6013514512738'	,
'6013514514638'	,
'60135145E3838'	,
'60135145F1738'	,
'60135145F8938'	,
'6013514708338'	,
'6013514729938'	,
'6013514799038'	,
'6013514807938'	,
'6013514808938'	,
'6013514812038'	,
'6013531007938'	,
'6013531099038'	,
'6013531107938'	,
'6013531129938'	,
'6013531199038'	,
'6013531203538'	,
'6013531207938'	,
'6013535706938'	,
'6013535706952'	,
'6013535707938'	,
'6013535707952'	,
'6013535709838'	,
'6013535709852'	,
'6013540116738'	,
'6013540127538'	,
'6013540129938'	,
'6013540174638'	,
'6013541706938'	,
'6013541707938'	,
'6013541729938'	,
'6013541799038'	,
'6013542106138'	,
'6013542106142'	,
'6013542108338'	,
'6013542108342'	,
'6013542112738'	,
'6013542112742'	,
'6013542123038'	,
'6013542123042'	,
'6013542125238'	,
'6013542125242'	,
'6013542155338'	,
'6013542155342'	,
'6013542199038'	,
'6013542199042'	,
'60135421C5438'	,
'60135421C5442'	,
'60135421E3838'	,
'60135421E3842'	,
'6013542207938'	,
'6013542229938'	,
'6013542299038'	,
'6013542301738'	,
'6013542307938'	,
'6013542329938'	,
'6013542407938'	,
'6013542429938'	,
'60135424E2838'	,
'6013542505838'	,
'6013542507938'	,
'6013542529938'	,
'6013542605838'	,
'6013542607938'	,
'6013542608338'	,
'6013542609838'	,
'6013542629938'	,
'6013542699038'	,
'6013542707938'	,
'6013542807938'	,
'6013542829938'	,
'6013542899038'	,
'6013542905838'	,
'6013542907938'	,
'6013542929938'	,
'6013543005838'	,
'6013543006938'	,
'6013543007938'	,
'6013543010838'	,
'6013543089338'	,
'6013543099038'	,
'6013543099138'	,
'6013543401738'	,
'6013543405838'	,
'6013543407938'	,
'6013550902338'	,
'6013550907938'	,
'6013550908938'	,
'6013551007938'	,
'6013551102338'	,
'6013551107938'	,
'6013551202338'	,
'6013551207938'	,
'6013551302338'	,
'6013551307938'	,
'6013551308938'	,
'6013551407938'	,
'6013551411238'	,
'6013551429938'	,
'6013551602328'	,
'6013551607928'	,
'6013551611228'	,
'6013551705138'	,
'6013551707438'	,
'6013551707938'	,
'6013551807938'	,
'6013551907928'	,
'6013551907938'	,
'6013551911228'	,
'6013551929938'	,
'6013552006938'	,
'6013552007938'	,
'6013552011238'	,
'6013552029938'	,
'6013552105138'	,
'6013552105838'	,
'6013552108338'	,
'6013552111238'	,
'6013552129938'	,
'6013552199038'	,
'6013552706938'	,
'6013552707938'	,
'6013552711238'	,
'6013552729938'	,
'6013552808338'	,
'6013552809538'	,
'6013552829938'	,
'6013552899038'	,
'60135528E0538'	,
'6013553002338'	,
'6013553007938'	,
'6013553008938'	,
'6013553012038'	,
'6013553102338'	,
'6013553107938'	,
'6013553108938'	,
'6013553112038'	,
'6013555307938'	,
'6013555329938'	,
'6013555399038'	,
'6013555507938'	,
'6013555529938'	,
'6013555607938'	,
'6013555608938'	,
'6013556007938'	,
'6013556029938'	,
'6013563007938'	,
'6013563099038'	,
'6013563107938'	,
'6013563129938'	,
'6013563199038'	,
'6013563207938'	,
'6013563208938'	,
'6013563212038'	,
'6013563307938'	,
'6013563308938'	,
'6013563312038'	,
'6013563407938'	,
'6013563429938'	,
'6013563499038'	,
'6013563501738'	,
'6013563507938'	,
'6013563529938'	,
'6013563602306'	,
'6013563602338'	,
'6013563705806'	,
'6013563705838'	,
'6013563707906'	,
'6013563707938'	,
'6013563709806'	,
'6013563729906'	,
'6013563729938'	,
'6013563907938'	,
'6013563929938'	,
'6013563999038'	,
'6013564002338'	,
'6013564005838'	,
'6013564007938'	,
'6013564049938'	,
'6013564103138'	,
'6013564116738'	,
'6013564149938'	,
'6013564202338'	,
'6013564207938'	,
'6013564209838'	,
'6013564299038'	,
'60135643E3703'	,
'60135643E3706'	,
'6013564405138'	,
'6013564405838'	,
'6013564407938'	,
'6013564497738'	,
'6013564499038'	,
'6013564505138'	,
'6013564505838'	,
'6013564507938'	,
'6013564529938'	,
'6013564599038'	,
'6013564702338'	,
'6013564707938'	,
'6013568105838'	,
'6013568107938'	,
'6013568129938'	,
'6013568199038'	,
'6013568205838'	,
'6013568207938'	,
'6013568299038'	,
'6013568305838'	,
'6013568307938'	,
'6013568329938'	,
'6013568399038'	,
'6013573507938'	,
'6013573529938'	,
'6013573599038'	,
'6013573601738'	,
'6013573606938'	,
'6013573607938'	,
'6013573707938'	,
'6013573708938'	,
'6013573712038'	,
'6013573807938'	,
'6013573829938'	,
'6013573907938'	,
'6013573972938'	,
'60135739A4538'	,
'60135739C6538'	,
'6013574005161'	,
'6013574006961'	,
'6013574007961'	,
'6013574010461'	,
'6013574103538'	,
'6013574106938'	,
'6013574203538'	,
'6013574207938'	,
'6013574209838'	,
'6013574301738'	,
'6013574302338'	,
'6013574307938'	,
'6013574401738'	,
'6013574402338'	,
'6013574407938'	,
'6013574409538'	,
'6013574531138'	,
'60135745C9338'	,
'60135745E6738'	,
'60135745Z6638'	,
'6013574607938'	,
'6013574608938'	,
'6013574612038'	,
'60135746C9338'	,
'60135746E6738'	,
'6013574705838'	,
'6013574707938'	,
'6013574729938'	,
'6013574799038'	,
'6013574805838'	,
'6013574806938'	,
'6013574807938'	,
'6013574829938'	,
'6013574959538'	,
'6013576105838'	,
'6013576129938'	,
'6013576156338'	,
'6013576158838'	,
'60135761Z9338'	,
'6013576203542'	,
'6013576205842'	,
'6013576207942'	,
'6013576306938'	,
'6013576307938'	,
'6013576407938'	,
'6013576408938'	,
'6013576412038'	,
'6013576506938'	,
'6013576507938'	,
'6013576509538'	,
'6013576605838'	,
'6013576606938'	,
'6013576607938'	,
'6013576703538'	,
'6013576706938'	,
'6013576707938'	,
'6013576729938'	,
'6013576803538'	,
'6013576807938'	,
'6013576902338'	,
'6013576907938'	,
'6013577002338'	,
'6013577007938'	,
'6013577107938'	,
'6013577160238'	,
'6013577171438'	,
'60135771C5038'	,
'6013577207938'	,
'6013577302342'	,
'6013577307942'	,
'6013577409837'	,
'6013577409861'	,
'6013577411237'	,
'6013577411261'	,
'6013579907938'	,
'6013579908938'	,
'6013579912038'	,
'6013580002338'	,
'6013580007938'	,
'6013580107938'	,
'6013580129938'	,
'6013580902303'	,
'6013580902328'	,
'6013580905103'	,
'6013580905128'	,
'6013580907903'	,
'6013580907928'	,
'6013581007903'	,
'6013581007928'	,
'6013581008903'	,
'6013581008928'	,
'6013581009803'	,
'6013581012003'	,
'6013581012028'	,
'6013581107938'	,
'6013581129938'	,
'6013581199038'	,
'6013581202938'	,
'6013581203038'	,
'6013581208338'	,
'6013581249938'	,
'6013581302338'	,
'6013581302938'	,
'6013581303138'	,
'6013581349938'	,
'6013582607937'	,
'6013582607961'	,
'6013582608937'	,
'6013582608961'	,
'6013582612037'	,
'6013582612061'	,
'6013582702338'	,
'6013582707938'	,
'6013582708938'	,
'6013582712038'	,
'6013582805838'	,
'6013582807938'	,
'6013582829938'	,
'6013582899038'	,
'6013583007938'	,
'6013583008938'	,
'6013583012038'	,
'6013583101738'	,
'6013583106938'	,
'6013583107938'	,
'6013583206938'	,
'6013583207938'	,
'6013583210838'	,
'6013583307938'	,
'6013583329938'	,
'6013583401738'	,
'6013583407938'	,
'6013583429938'	,
'6013583603438'	,
'6013583606938'	,
'6013583607938'	,
'6013583802328'	,
'6013583806928'	,
'6013583807928'	,
'6013583907928'	,
'60135839A4528'	,
'6013584007938'	,
'60135840A4538'	,
'60135841A4538'	,
'6013584207938'	,
'6013584307938'	,
'60135844A4538'	,
'6013584507938'	,
'6013584602338'	,
'6013584607938'	,
'6013584807938'	,
'6013584899038'	,
'6013585307938'	,
'60135853A4538'	,
'6013596202938'	,
'6013596302338'	,
'6013596307938'	,
'6013596399038'	,
'6013596402338'	,
'6013596407938'	,
'6013596499038'	,
'6013596501738'	,
'6013596507938'	,
'6013596599038'	,
'6013596602338'	,
'6013596607938'	,
'6013596612038'	,
'6013596699038'	,
'6013596706938'	,
'6013596707938'	,
'6013596709538'	,
'6013596802938'	,
'6013596907938'	,
'6013596909538'	,
'6013596999038'	,
'6013597007938'	,
'6013597029938'	,
'6013976307938'	,
);


function getCompra($co_art)
{


    $serverName = "172.16.1.19";
    $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
    $conn = sqlsrv_connect($serverName, $connectionInfo);
    $sql = "SELECT top 1  co_art,fact_num,fec_lote,total_art from reng_com  where co_art ='$co_art' order by fec_lote desc";
    $consulta = sqlsrv_query($conn, $sql);


    while ($row = sqlsrv_fetch_array($consulta)) {

        $co_col []=  $row;
    }

    $res = $co_col;
    return $res;
}
echo "$chontel[427]";
$res = getCompra($chontel[427]);
var_dump($res);

echo "      <tr>
<th scope='col'>#</th>
<th scope='col'>Codigo</th>
<th scope='col'>factura</th>
<th scope='col'>fecha</th>
<th scope='col'>total</th>
</tr>
</thead>
<tbody>";
$n=1;
for ($i=0; $i < count($chontel); $i++) {
    
    $res = getCompra($chontel[$i]);
    
    echo "        <tr>
    <th scope='row'> $n </th>
    <td> $res[0] </td>
    <td> $res[1]  </td>
    <td> $res[2]  </td>
    <td> $res[3]  </td>
    </tr>";
    
    $n++;
}



?>
      </tbody>
  </table>
</body>
</html>