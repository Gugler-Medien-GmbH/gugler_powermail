<?php

declare(strict_types=1);

namespace Gugler\GuglerPowermail\Captcha;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Site\Entity\Site;

/**
 * Reusable captcha.eu integration - not tied to powermail. Any extension that
 * embeds a form can inject this service to render a captcha.eu challenge
 * (via CaptchaEuFieldViewHelper) and verify the submitted solution server-side.
 *
 * All configuration (publicKey/restKey/mode/theme) comes from this extension's
 * Site Set ("gugler/gugler-powermail", Configuration/Sets/CaptchaEu/) - a site
 * only sees/fills these in once it lists the set under `dependencies` in its
 * config/sites/<site>/config.yaml, then edits the values via the Site
 * Settings editor (Site Management > Sites > <site> > Settings). That makes
 * publicKey/restKey settable per site (useful here: this project runs two
 * sites, raumordnung + statistik-noe, which may end up needing different
 * captcha.eu accounts) without touching TypoScript or Extension Configuration
 * at all.
 */
class CaptchaEuService
{
    private const VALIDATE_URL = 'https://www.captcha.eu/validate';

    /**
     * POST field name the widget/interceptor script writes its solution/response
     * token into - read this from $request/$_POST and pass it to verify().
     */
    public const SOLUTION_FIELD_NAME = 'captcha_at_solution';

    public function __construct(
        private readonly RequestFactory $requestFactory,
    ) {
    }

    public function getPublicKey(): string
    {
        return (string)$this->getSetting('captchaeu.publicKey', '');
    }

    public function getMode(): string
    {
        return (string)$this->getSetting('captchaeu.mode', 'invisible');
    }

    public function getTheme(): string
    {
        return (string)$this->getSetting('captchaeu.theme', 'auto');
    }

    public function isConfigured(): bool
    {
        return $this->getPublicKey() !== '';
    }

    /**
     * Verify a submitted solution against the captcha.eu REST API.
     * Field names for $solution: POST field "captcha_at_solution" (invisible mode)
     * or the widget's response token (widget mode) - both are validated the same way.
     */
    public function verify(string $solution): bool
    {
        if ($solution === '') {
            return false;
        }

        $restKey = (string)$this->getSetting('captchaeu.restKey', '');
        if ($restKey === '') {
            return false;
        }

        try {
            $response = $this->requestFactory->request(self::VALIDATE_URL, 'POST', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Rest-Key' => $restKey,
                ],
                'body' => $solution,
            ]);
        } catch (\Throwable) {
            return false;
        }

        $result = json_decode((string)$response->getBody(), false);
        return (bool)($result->success ?? false);
    }

    private function getSetting(string $identifier, string $default): mixed
    {
        $request = $GLOBALS['TYPO3_REQUEST'] ?? null;
        if (!$request instanceof ServerRequestInterface) {
            return $default;
        }

        $site = $request->getAttribute('site');
        if (!$site instanceof Site) {
            return $default;
        }

        return $site->getSettings()->get($identifier, $default);
    }
}
