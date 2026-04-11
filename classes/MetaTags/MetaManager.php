<?php
namespace MetaTags;

class MetaManager {

	protected array $meta = [];
	protected array $site = [];

	public function __construct() {
		$site = elgg_get_site_entity();

		$site_name = $this->safe($site->getDisplayName());
		$site_description = $this->safe($site->description);
		$site_url = $this->safe($site->getURL());
    
    $this->site = [
      'name' => $site_name,
      'description' => $site_description,
      'url' => $site_url,
      'keywords' => $this->safe(elgg_get_plugin_setting("mainpage_keywords", "metatags")),
      'image' => $this->safe(elgg_get_plugin_setting("mainpage_image", "metatags")),
      'email' => $this->safe(elgg_get_plugin_setting("mainpage_email", "metatags")),
      'phone_number' => $this->safe(elgg_get_plugin_setting("mainpage_phone_number", "metatags")),
      'fax_number' => $this->safe(elgg_get_plugin_setting("mainpage_fax_number", "metatags")),
      'locality' => $this->safe(elgg_get_plugin_setting("mainpage_locality", "metatags")),
      'region' => $this->safe(elgg_get_plugin_setting("mainpage_region", "metatags")),
      'postal' => $this->safe(elgg_get_plugin_setting("mainpage_postal", "metatags")),
      'country' => $this->safe(elgg_get_plugin_setting("mainpage_country", "metatags")),
			'facebook' => $this->safe(elgg_get_plugin_setting("mainpage_facebook", "metatags")),
      'twitter' => $this->safe(elgg_get_plugin_setting("mainpage_twitter", "metatags")),
			'linkedin' => $this->safe(elgg_get_plugin_setting("mainpage_linkedin", "metatags")),
      'instagram' => $this->safe(elgg_get_plugin_setting("mainpage_instagram", "metatags")),
			'youtube' => $this->safe(elgg_get_plugin_setting("mainpage_youtube", "metatags")),
			'pintrest' => $this->safe(elgg_get_plugin_setting("mainpage_pintrest", "metatags")),
    ];

		$this->meta = [
			'language' => $this->safe(elgg_get_config('language')),
			'robots' => 'index,follow',

			'url' => $site_url,
			'identifier-URL' => $site_url,

			'keywords' => $this->safe(elgg_get_plugin_setting("mainpage_keywords", "metatags")),
			'description' => $site_description,

			// Optional
			'revised' => '',
			'topic' => '',
			'summary' => '',
			'author' => '',
			'owner' => '',

			// Open Graph
			'og:title' => $site_name,
			'og:url' => $site_url,
			'og:site_name' => $site_name,
			'og:email' => $this->safe(elgg_get_plugin_setting("mainpage_email", "metatags")),
			'og:phone_number' => $this->safe(elgg_get_plugin_setting("mainpage_phone_number", "metatags")),
			'og:fax_number' => $this->safe(elgg_get_plugin_setting("mainpage_fax_number", "metatags")),
			'og:locality' => $this->safe(elgg_get_plugin_setting("mainpage_locality", "metatags")),
			'og:region' => $this->safe(elgg_get_plugin_setting("mainpage_region", "metatags")),
			'og:postal-code' => $this->safe(elgg_get_plugin_setting("mainpage_postal", "metatags")),
			'og:country-name' => $this->safe(elgg_get_plugin_setting("mainpage_country", "metatags")),
			'og:type' => '',
			'og:image' => $this->safe(elgg_get_plugin_setting("mainpage_image", "metatags")),
			'og:description' => $site_description,

			// Twitter
			'twitter:card' => 'summary_large_image',
			'twitter:title' => $site_name,
			'twitter:description' => $site_description,
			'twitter:url' => $site_url,
			'twitter:image' => $this->safe(elgg_get_plugin_setting("mainpage_image", "metatags")),
		];
	}

	/**
	 * Ensure value is never null/empty
	 */
	protected function safe($value): string {
		if (is_null($value)) {
			return '';
		}

		if (is_string($value)) {
			$value = trim($value);
			return $value === '' ? '' : $value;
		}

		return (string) $value;
	}

	/**
	 * Set single value
	 */
	public function set(string $key, $value): self {
		$this->meta[$key] = $this->safe($value);
		return $this;
	}

  /**
	 * Set URL value
	 */
	public function setURL(string $value): self {
		$this->meta['url'] = $this->safe($value);
		$this->meta['identifier-URL'] = $this->safe($value);
		$this->meta['og:url'] = $this->safe($value);
		$this->meta['twitter:url'] = $this->safe($value);
		return $this;
	}
  
  /**
	 * Set Title value
	 */
	public function setTitle(string $value): self {
		$this->meta['og:title'] = $this->safe($value);
		$this->meta['twitter:title'] = $this->safe($value);
		return $this;
	}
  
  /**
	 * Set Description value
	 */
	public function setDescription(string $value): self {
		$this->meta['description'] = $this->safe($value);
		$this->meta['og:description'] = $this->safe($value);
		$this->meta['twitter:description'] = $this->safe($value);
		return $this;
	}
  
  /**
	 * Set Keywords value
	 */
	public function setKeywords(string $value): self {
		$this->meta['keywords'] = $this->safe($value);
		return $this;
	}
  
  /**
	 * Set Image value
	 */
	public function setImage(string $value): self {
		$this->meta['og:image'] = $this->safe($value);
		$this->meta['twitter:image'] = $this->safe($value);
		return $this;
	}
  
  /**
	 * Add Keywords to existing list
	 */
	public function addKeyword(string $value): self {
    $tags = [];
    $tags = array_map('trim', explode(",", $this->meta['keywords']));
		$tags = array_merge($tags, (array) trim($value));
		$this->meta['keywords'] = implode(", ", $tags);
		return $this;
	}
  
	/**
	 * Get all
	 */
	public function getAll(): array {
		return $this->meta;
	}

	/**
	 * Get one
	 */
	public function get(string $key, $default = '') {
		return $this->meta[$key] ?? $default;
	}
  
  /**
	 * Get site details
	 */
   public function getSite($key, $default = '') {
		return $this->site[$key] ?? $default;
	}
}