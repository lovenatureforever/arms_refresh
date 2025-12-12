<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CosecTemplate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'form_type',
        'template_path',
        'content',
        'template_file',
        'signature_type',
        'default_signatory_id',
        'credit_cost',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Signature types
    const SIGNATURE_SOLE_DIRECTOR = 'sole_director';
    const SIGNATURE_TWO_DIRECTORS = 'two_directors';
    const SIGNATURE_ALL_DIRECTORS = 'all_directors';

    public function defaultSignatory()
    {
        return $this->belongsTo(CompanyDirector::class, 'default_signatory_id');
    }

    public function orders()
    {
        return $this->hasMany(CosecOrder::class, 'template_id');
    }

    /**
     * Extract all placeholders from template content.
     * Placeholders are in format {{placeholder_name}}
     */
    public function getPlaceholders(): array
    {
        preg_match_all('/\{\{([^}]+)\}\}/', $this->content, $matches);
        return array_unique($matches[1]);
    }

    /**
     * Get placeholders categorized by type.
     */
    public function getCategorizedPlaceholders(): array
    {
        $placeholders = $this->getPlaceholders();

        $categories = [
            'company' => [],      // Auto-filled from company record
            'director' => [],     // Director selection/signature placeholders (auto-filled from selected directors)
            'secretary' => [],    // Secretary info (auto-filled)
            'member' => [],       // Member/shareholder info (auto-filled)
            'custom' => [],       // User input required
        ];

        // Company placeholders (auto-filled) - exact matches
        $companyExact = ['company_name', 'company_no', 'company_old_no', 'company_address'];

        // Director/signature placeholders - patterns that match numbered suffixes like _1, _2, _3
        // e.g., director_signature_1, director_name_2, signer_name_1
        $directorRegex = '/^(director_signature|director_name|signer_name)_\d+$/i';

        // Member/shareholder placeholders - patterns with numbered suffixes
        // e.g., member_signature_1, member_name_1, member_shares_1, member_percentage_1
        $memberRegex = '/^(member_signature|member_name|member_shares|member_percentage)_\d+$/i';

        // Secretary placeholders (auto-filled) - prefix patterns
        $secretaryPatterns = [
            'secretary_signature', 'secretary_name', 'secretary_license', 'secretary_ssm',
            'secretary_company', 'secretary_address', 'secretary_email'
        ];

        foreach ($placeholders as $placeholder) {
            $matched = false;

            // Check exact company matches
            if (in_array(strtolower($placeholder), array_map('strtolower', $companyExact))) {
                $categories['company'][] = $placeholder;
                continue;
            }

            // Check director patterns with numbered suffix
            if (preg_match($directorRegex, $placeholder)) {
                $categories['director'][] = $placeholder;
                continue;
            }

            // Check member patterns with numbered suffix
            if (preg_match($memberRegex, $placeholder)) {
                $categories['member'][] = $placeholder;
                continue;
            }

            // Check secretary patterns (prefix match)
            foreach ($secretaryPatterns as $pattern) {
                if (stripos($placeholder, $pattern) === 0) {
                    $categories['secretary'][] = $placeholder;
                    $matched = true;
                    break;
                }
            }

            if ($matched) continue;

            // Everything else is custom input
            $categories['custom'][] = $placeholder;
        }

        return $categories;
    }

    /**
     * Get user-fillable placeholders (excluding auto-filled ones).
     */
    public function getUserInputPlaceholders(): array
    {
        $categories = $this->getCategorizedPlaceholders();
        return $categories['custom'];
    }

    /**
     * Get all editable placeholders (excluding signatures which are images).
     */
    public function getAllEditablePlaceholders(): array
    {
        $categories = $this->getCategorizedPlaceholders();
        $editable = [];

        // Add company placeholders
        foreach ($categories['company'] as $p) {
            $editable[] = $p;
        }

        // Add secretary placeholders (exclude signatures)
        foreach ($categories['secretary'] as $p) {
            if (stripos($p, 'signature') === false) {
                $editable[] = $p;
            }
        }

        // Add custom placeholders
        foreach ($categories['custom'] as $p) {
            $editable[] = $p;
        }

        return $editable;
    }

    /**
     * Get number of director signatures required.
     */
    public function getRequiredDirectorSignatures(): int
    {
        $categories = $this->getCategorizedPlaceholders();
        $signaturePlaceholders = array_filter($categories['director'], function($p) {
            return stripos($p, 'signature') !== false;
        });
        return count($signaturePlaceholders);
    }

    /**
     * Get the template content.
     */
    public function getTemplateContent(): string
    {
        return $this->content ?? '';
    }

    /**
     * Replace placeholders with actual values.
     */
    public function fillPlaceholders(array $values): string
    {
        $content = $this->content;

        foreach ($values as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value, $content);
        }

        return $content;
    }

    /**
     * Convert placeholder name to human-readable label.
     */
    public static function placeholderToLabel(string $placeholder): string
    {
        // Remove common prefixes
        $label = preg_replace('/^(new_|old_)/', '', $placeholder);

        // Replace underscores with spaces and capitalize
        $label = str_replace('_', ' ', $label);
        $label = ucwords($label);

        return $label;
    }
}
