<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Scrapper;

class ScrapperTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testRadiosarajevo()
    {
        $scrapper = new Scrapper();
        $parser = $scrapper->scrap('http://www.radiosarajevo.ba/novost/218062/predsjednistvo-bih-uradimo-malo-vise-za-bih-i-svima-ce-nam-biti-bolje-foto');

        $this->assertTrue(starts_with($parser->title(), 'Predsjedništvo BiH: Uradimo malo više za BiH i svima će nam biti bolje (FOTO)'));
        $this->assertTrue(str_contains($parser->content(), 'Predsjedništvo BiH: Uradimo malo više za BiH i svima će nam biti bolje (FOTO)'), 'Content not match');
        $this->assertTrue(str_contains($parser->description(), 'Portal Radiosarajevo.ba jedan je'));
        $this->assertTrue($parser->isArticle());
        $this->assertEquals($parser->date(), '2016-03-01');

    }







    public function testUrls()
    {
        $urls = [
            'http://sportsport.ba/bh_fudbal/sloboda-spremna-za-radnik-skahic-odradio-laksi-trening/186369',
            'http://novi.ba/clanak/58862/sasa-mirkovic-za-nas-je-republika-srpska-dio-srbije',
            'http://vijesti.ba/clanak/300085/hdz-ovi-ministri-u-vladi-fbih-nisu-kvalitetno-radili-imamo-probleme-u-komunikaciji',
            'http://depo.ba/clanak/144306/cetiri-osobe-uhapsene-u-gornjem-vakufu-nacelniku-opcine-prijetili-otmicom-kcerke',
            'http://www.source.ba/clanak/Crnahronika/396370/Muriz-Brkic-je-davio-Arnelu-%C4%90ogic-a-onda-joj-je-zadao-smrtonosne-ubode-nozem-u-vrat--Nakon-ubistva-snimljeni-kako-pjevaju-i-piju',
            'http://www.bh-index.com/iako-je-doslo-do-pada-uvoza-i-rasta-izvoza-trgovinski-deficit-bih-u-2015-686-milijardi-km/',
            //ODDS 'http://www.oslobodjenje.ba/kolumne/reagiranje-treci-kanal-repriza-davno-zamisljenog-nauma',
            'http://www.krajina.ba/201836/podignuta-optuznica-protiv-sase-curguza-za-zlocine-u-bihacu/',
            'http://www.haber.ba/lifestyle/zdravlje/275164-neobicni-simptomi-raka-u-ranoj-fazi',
            'http://www.hayat.ba/vijest.php?id=23842',
            'http://ba.n1info.com/a84686/Sport-Klub/Nogomet/Prica-o-susretu-Gatussa-i-Ronalda-u-Ligi-prvaka.html',
            'http://www.glassrpske.com/novosti/vijesti_dana/Petrodolari-izmicu-losim-diplomatama/204159.html',
            'http://faktor.ba/prosirena-istraga-zbog-tragedije-u-dubravama-rukovodioci-rudnika-odgovorni-za-pogibiju-mladica/',
            'http://www.cazin.net/clanci/vijest/konkurs-agroholding-d-o-o-cazin-trazi-20-radnika',
            //'http://www.biscani.net/sindikati-vjeruju-u-novi-sporazum-ponuden-iz-vlade-nazire-li-se-kraj-strajka-u-skolama/',
            //'http://www.biscani.net/ramo-brkic-bivsi-komesar-usk-policije-pobjegao-iz-zemlje-izbjegavsi-11-godina-zatvora/',
            'http://www.radiosarajevo.ba/novost/218163/skinimo-okove-o-cemu-se-radi',
            'http://www.vecernji.ba/avion-prinudno-sletio-u-sarajevo-zbog-pijanih-putnika-1063291',
            'http://www.vecernji.ba/sok-u-srbiji-ruske-baze-u-nisu-uskoro-ce-pod-kontrolu-vojnika-nato-a-iz-hrvatske-1064373',
            'http://abc.ba/novost/41062/unsko-sanski-kanton-bogatiji-za-jos-jednoga-doktora-lingvistickih-nauka-',
            'http://www.ekskluziva.ba/Tesko-je-povjerovati-Da-li-je-realno-da-je-Isidora-Bjelica-ovo-rekla-o-svojoj-bolesti-/86791.html',
            'http://bljesak.info/rubrika/vijesti/clanak/bivsi-komesar-policije-ramo-brkic-pobjegao-iz-bih/149952',
            'http://www.fokus.ba/245566/vijesti/globus/assad-nudi-amnestiju-opozicionim-borcima/',
            'http://balkans.aljazeera.net/vijesti/merkel-i-oreskovic-za-jacanje-ekonomskih-veza',
            'http://www.klix.ba/vijesti/crna-hronika/ubice-arnele-djogic-predate-tuzilastvu-zdk/160301118',
            'http://www.avaz.ba/clanak/222732/stravican-zlocin-ovo-su-mladici-koji-su-priznali-da-su-ubili-arnelu-dogic-i-bacili-je-s-litice',
            'http://www.radiosarajevo.ba/novost/218062/predsjednistvo-bih-uradimo-malo-vise-za-bih-i-svima-ce-nam-biti-bolje-foto',
            'http://www.nezavisne.com/novosti/svijet/Tusk-ce-od-Ankare-traziti-vece-angazovanje-u-migrantskoj-krizi/357041',
        ];

        $scrapper = new Scrapper();
        foreach($urls as $url){
            $parser = $scrapper->scrap($url);
            echo "Test Scrapping: $url \n";

            $this->assertTrue($parser->isArticle(), "Error finding Article in $url ");
            $this->assertTrue($parser->title() != '');
            $this->assertTrue($parser->content() != '');
            $this->assertTrue($parser->description() != '', $parser->description());

            $this->assertContains('2016', $parser->date(),  $parser->date());
            $this->assertTrue(count($parser->social()) == 4);
            //echo $parser->date();
        }


    }
}
