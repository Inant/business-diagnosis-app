<?php

if (!function_exists('formatAnalysisContent')) {
    function formatAnalysisContent($content) {
        // Clean up the content first
        $content = trim($content);

        // Split content into lines for better processing
        $lines = explode("\n", $content);
        $formatted = [];
        $inList = false;
        $listType = '';

        foreach ($lines as $line) {
            $line = trim($line);

            // Skip empty lines but preserve spacing
            if (empty($line)) {
                if ($inList) {
                    $formatted[] = $listType === 'ul' ? '</ul>' : '</ol>';
                    $inList = false;
                }
                $formatted[] = '<br>';
                continue;
            }

            // Handle section dividers (---)
            if (preg_match('/^-{3,}$/', $line)) {
                if ($inList) {
                    $formatted[] = $listType === 'ul' ? '</ul>' : '</ol>';
                    $inList = false;
                }
                $formatted[] = '<div class="section-divider"><hr class="border-gray-300 my-6"></div>';
                continue;
            }

            // Handle main headers with ### (make them standout)
            if (preg_match('/^#{3}\s*(.+?)\s*#{3}$/', $line, $matches)) {
                if ($inList) {
                    $formatted[] = $listType === 'ul' ? '</ul>' : '</ol>';
                    $inList = false;
                }
                $formatted[] = '<div class="main-header bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-4 py-3 rounded-lg mb-4 mt-6">';
                $formatted[] = '<h2 class="text-lg font-bold mb-0">' . htmlspecialchars($matches[1]) . '</h2>';
                $formatted[] = '</div>';
                continue;
            }

            // Handle sub headers with ** at start **
            if (preg_match('/^\*\*(\d+\.\s*.+?)\*\*:?\s*$/', $line, $matches)) {
                if ($inList) {
                    $formatted[] = $listType === 'ul' ? '</ul>' : '</ol>';
                    $inList = false;
                }
                $formatted[] = '<h3 class="text-md font-semibold text-gray-800 mt-5 mb-3 border-l-4 border-blue-500 pl-3">' . htmlspecialchars($matches[1]) . '</h3>';
                continue;
            }

            // Handle field labels with backticks (`field_name`:)
            if (preg_match('/^\s*\*?\s*`([^`]+)`\s*:?\s*(.*)$/', $line, $matches)) {
                if ($inList) {
                    $formatted[] = $listType === 'ul' ? '</ul>' : '</ol>';
                    $inList = false;
                }
                $fieldName = $matches[1];
                $fieldValue = trim($matches[2]);

                // Format field name for display
                $displayName = str_replace('_', ' ', $fieldName);
                $displayName = ucwords($displayName);

                $formatted[] = '<div class="field-item bg-gray-50 rounded-lg p-3 mb-2 border-l-4 border-green-500">';
                $formatted[] = '<span class="field-label font-medium text-green-700">' . htmlspecialchars($displayName) . ':</span>';

                if (!empty($fieldValue)) {
                    // Process the field value for any formatting
                    $fieldValue = processInlineFormatting($fieldValue);
                    $formatted[] = '<span class="field-value text-gray-800 ml-2">' . $fieldValue . '</span>';
                }
                $formatted[] = '</div>';
                continue;
            }

            // Handle bullet points with * or -
            if (preg_match('/^\s*[\*\-]\s*(.+)$/', $line, $matches)) {
                if (!$inList || $listType !== 'ul') {
                    if ($inList && $listType === 'ol') {
                        $formatted[] = '</ol>';
                    }
                    $formatted[] = '<ul class="list-disc pl-6 mb-3 space-y-1">';
                    $inList = true;
                    $listType = 'ul';
                }
                $content = processInlineFormatting($matches[1]);
                $formatted[] = '<li class="text-gray-700">' . $content . '</li>';
                continue;
            }

            // Handle numbered lists
            if (preg_match('/^\s*\d+\.\s*(.+)$/', $line, $matches)) {
                if (!$inList || $listType !== 'ol') {
                    if ($inList && $listType === 'ul') {
                        $formatted[] = '</ul>';
                    }
                    $formatted[] = '<ol class="list-decimal pl-6 mb-3 space-y-1">';
                    $inList = true;
                    $listType = 'ol';
                }
                $content = processInlineFormatting($matches[1]);
                $formatted[] = '<li class="text-gray-700">' . $content . '</li>';
                continue;
            }

            // Handle nested bullet points with spaces
            if (preg_match('/^\s{4,}[\*\-]\s*(.+)$/', $line, $matches)) {
                if ($inList) {
                    $content = processInlineFormatting($matches[1]);
                    $formatted[] = '<li class="text-gray-600 ml-4 text-sm">' . $content . '</li>';
                    continue;
                }
            }

            // Regular paragraph
            if ($inList) {
                $formatted[] = $listType === 'ul' ? '</ul>' : '</ol>';
                $inList = false;
            }

            $processedLine = processInlineFormatting($line);
            $formatted[] = '<p class="text-gray-800 mb-3 leading-relaxed">' . $processedLine . '</p>';
        }

        // Close any open lists
        if ($inList) {
            $formatted[] = $listType === 'ul' ? '</ul>' : '</ol>';
        }

        $result = implode("\n", $formatted);

        return '<div class="analysis-content space-y-2">' . $result . '</div>';
    }
}

if (!function_exists('processInlineFormatting')) {
    function processInlineFormatting($text) {
        // Handle bold text (**text**)
        $text = preg_replace('/\*\*([^*]+)\*\*/', '<strong class="font-bold text-gray-900">$1</strong>', $text);

        // Handle italic text (*text*) but not if it's already processed as bold
        $text = preg_replace('/(?<!\*)\*([^*]+)\*(?!\*)/', '<em class="italic text-blue-700">$1</em>', $text);

        // Handle inline code with backticks
        $text = preg_replace('/`([^`]+)`/', '<code class="bg-gray-200 px-2 py-1 rounded text-sm font-mono text-purple-700">$1</code>', $text);

        // Handle special formatting for measurements/percentages
        $text = preg_replace('/(\d+%|\d+\s*detik|\d+\s*km)/', '<span class="font-semibold text-blue-600">$1</span>', $text);

        // Handle quotes or highlighted phrases in parentheses
        $text = preg_replace('/\(([^)]+)\)/', '<span class="text-gray-600 text-sm">($1)</span>', $text);

        // Handle colon-separated key-value pairs better
        $text = preg_replace('/^([^:]+):\s*(.+)$/', '<span class="font-medium text-gray-700">$1:</span> <span class="text-gray-800">$2</span>', $text);

        return $text;
    }
}

if (!function_exists('formatAnalysisContentWithJSON')) {
    function formatAnalysisContentWithJSON($content) {
        // Regex untuk mendeteksi blok JSON yang dibungkus backticks
        $pattern = '/```?json\s*(\{.*?\})\s*```?/s';

        $formattedContent = preg_replace_callback($pattern, function($matches) {
            $jsonString = $matches[1];
            $jsonData = json_decode($jsonString, true);

            if ($jsonData) {
                return generateJsonPoints($jsonData);
            }
            return $matches[0]; // Return original jika JSON tidak valid
        }, $content);

        // Format konten lainnya (markdown to HTML)
        $formattedContent = nl2br($formattedContent);
        $formattedContent = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $formattedContent);
        $formattedContent = preg_replace('/###\s*(.*?)(?=\n|$)/', '<h3>$1</h3>', $formattedContent);

        return $formattedContent;
    }
}

if (!function_exists('generateJsonPoints')) {
    function generateJsonPoints($data) {
        $html = '<div class="json-summary-points" style="background: #f8f9fa; padding: 1.5rem; border-radius: 0.75rem; margin: 1.5rem 0; border-left: 4px solid #3b82f6;">';
        $html .= '<h4 style="color: #1f2937; font-weight: 600; margin-bottom: 1rem; display: flex; align-items: center;"><i class="fas fa-clipboard-list mr-2 text-blue-600"></i>Profil DNA Bisnis</h4>';

        foreach ($data as $key => $value) {
            $html .= '<div style="margin-bottom: 1rem;">';
            $html .= '<strong style="color: #374151;">' . formatLabel($key) . ':</strong><br>';
            $html .= '<span style="color: #6b7280; margin-left: 1rem;">' . formatValue($value) . '</span>';
            $html .= '</div>';
        }

        $html .= '</div>';
        return $html;
    }
}

if (!function_exists('formatLabel')) {
    function formatLabel($key) {
        $labels = [
            'Nama_Bisnis' => 'Nama Bisnis',
            'Deskripsi_Singkat' => 'Deskripsi Singkat',
            'Produk_Layanan_Utama' => 'Produk & Layanan Utama',
            'Target_Pasar_Spesifik' => 'Target Pasar',
            'Masalah_Kunci_yang_Diselesaikan' => 'Masalah yang Diselesaikan',
            'Kekuatan_Unik_Teridentifikasi' => 'Kekuatan Unik',
            'Tantangan_Strategis' => 'Tantangan Strategis',
            'Visi_Jangka_Panjang' => 'Visi Jangka Panjang'
        ];

        return $labels[$key] ?? str_replace('_', ' ', ucwords(str_replace('_', ' ', $key)));
    }
}

if (!function_exists('formatValue')) {
    function formatValue($value) {
        if (is_array($value)) {
            $items = array_map(function($item) {
                return '• ' . htmlspecialchars($item);
            }, $value);
            return implode('<br>', $items);
        }

        return htmlspecialchars($value);
    }
}
