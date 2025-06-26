<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterWidget extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'type',
        'data',
        'order',
        'is_active',
        'css_class'
    ];

    protected $casts = [
        'data' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * Scope untuk widget yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk mengurutkan berdasarkan order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    /**
     * Get widget berdasarkan type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get all active widgets ordered
     */
    public static function getActiveWidgets()
    {
        return self::active()->ordered()->get();
    }

    /**
     * Get widget types
     */
    public static function getTypes()
    {
        return [
            'text' => 'Teks Biasa',
            'links' => 'Daftar Link',
            'contact' => 'Informasi Kontak',
            'social' => 'Media Sosial',
            'custom' => 'HTML Kustom'
        ];
    }

    /**
     * Get formatted content based on type
     */
    public function getFormattedContentAttribute()
    {
        switch ($this->type) {
            case 'links':
                return $this->formatLinksContent();
            case 'contact':
                return $this->formatContactContent();
            case 'social':
                return $this->formatSocialContent();
            case 'custom':
                return $this->content;
            default:
                return nl2br(e($this->content));
        }
    }

    private function formatLinksContent()
    {
        if (!$this->data || !isset($this->data['links'])) {
            return '';
        }

        $html = '<ul class="list-unstyled">';
        foreach ($this->data['links'] as $link) {
            $html .= '<li><a href="' . e($link['url']) . '" class="text-light">' . e($link['text']) . '</a></li>';
        }
        $html .= '</ul>';

        return $html;
    }

    private function formatContactContent()
    {
        if (!$this->data) {
            return nl2br(e($this->content));
        }

        $html = '';
        if (isset($this->data['address'])) {
            $html .= '<p><i class="fas fa-map-marker-alt me-2"></i>' . e($this->data['address']) . '</p>';
        }
        if (isset($this->data['phone'])) {
            $html .= '<p><i class="fas fa-phone me-2"></i>' . e($this->data['phone']) . '</p>';
        }
        if (isset($this->data['email'])) {
            $html .= '<p><i class="fas fa-envelope me-2"></i>' . e($this->data['email']) . '</p>';
        }
        if (isset($this->data['whatsapp'])) {
            $html .= '<p><i class="fab fa-whatsapp me-2"></i>' . e($this->data['whatsapp']) . '</p>';
        }

        return $html;
    }

    private function formatSocialContent()
    {
        if (!$this->data || !isset($this->data['social'])) {
            return '';
        }

        $html = '<div class="d-flex gap-2">';
        foreach ($this->data['social'] as $social) {
            $icon = $this->getSocialIcon($social['platform']);
            $html .= '<a href="' . e($social['url']) . '" class="text-light" target="_blank">';
            $html .= '<i class="' . $icon . ' fa-lg"></i>';
            $html .= '</a>';
        }
        $html .= '</div>';

        return $html;
    }

    private function getSocialIcon($platform)
    {
        $icons = [
            'facebook' => 'fab fa-facebook',
            'twitter' => 'fab fa-twitter',
            'instagram' => 'fab fa-instagram',
            'linkedin' => 'fab fa-linkedin',
            'youtube' => 'fab fa-youtube',
            'whatsapp' => 'fab fa-whatsapp',
            'telegram' => 'fab fa-telegram',
        ];

        return $icons[$platform] ?? 'fas fa-link';
    }
}
