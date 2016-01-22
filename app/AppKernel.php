<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // Core
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new JMS\AopBundle\JMSAopBundle(),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),

            // Propel
            new Propel\PropelBundle\PropelBundle(),
            new Bazinga\Bundle\FakerBundle\BazingaFakerBundle(),

            // FOSUserBundle
            new FOS\UserBundle\FOSUserBundle(),
            
            // OAuthBundle
            new HWI\Bundle\OAuthBundle\HWIOAuthBundle(),
            
            // HTML2PDF
            new Ensepar\Html2pdfBundle\EnseparHtml2pdfBundle(),
            
            // Admin Generator & dependencies
            new Admingenerator\FormBundle\AdmingeneratorFormBundle(),
            new Admingenerator\FormExtensionsBundle\AdmingeneratorFormExtensionsBundle(),
            new Admingenerator\GeneratorBundle\AdmingeneratorGeneratorBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
    
            // Liip Imagine
            new Liip\ImagineBundle\LiipImagineBundle(),
            new Liip\UrlAutoConverterBundle\LiipUrlAutoConverterBundle(),
            
            // Géolocalisation
            new Bazinga\Bundle\GeocoderBundle\BazingaGeocoderBundle(),

            // HTML Purifier
            new Exercise\HTMLPurifierBundle\ExerciseHTMLPurifierBundle(),

            // Strength Password
            new Rollerworks\Bundle\PasswordStrengthBundle\RollerworksPasswordStrengthBundle(),

            // Project Bundle,
            new Politizr\FrontBundle\PolitizrFrontBundle(),
            new Politizr\AdminBundle\PolitizrAdminBundle(),
            new Politizr\CommandBundle\PolitizrCommandBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test', 'stage', 'debug'))) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Elao\WebProfilerExtraBundle\WebProfilerExtraBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
