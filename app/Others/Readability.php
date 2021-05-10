<?php
//
//use andreskrey\Readability\Configuration;
//use andreskrey\Readability\ParseException;
//use andreskrey\Readability\Readability as MozillaReadability;
//use Readability\Readability as JokerReadability;
//
//$readability = new MozillaReadability(new Configuration());
//
//try {
//    $readability->parse($html);
//    $this->console->info(sprintf('Título: %s', $readability->getTitle()));
//    $this->console->info(sprintf('Artigo: %s', $readability->getContent()));
//    $this->console->info(sprintf('Imagem: %s', $readability->getMainImage() ?? $readability->getImage()));
//} catch (ParseException $e) {
//    $this->console->error(sprintf('Error processing text: %s', $e->getMessage()));
//
//    $readability = new JokerReadability($html, $url);
//    // or without Tidy
//    // $readability = new JokerReadability($html, $url, 'libxml', false);
//    $result = $readability->init();
//    if ($result) {
//        $this->console->info(sprintf('Título: %s', $readability->getTitle()->textContent));
//        $this->console->info(sprintf('Artigo: %s', $readability->getContent()->textContent));
//    } else {
//        // @FIXME O que fazer quando nunca encontrar?
//        $this->console->error('Error processing text.');
//    }
//}
