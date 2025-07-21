<?php

if (!function_exists('formatAnalysisContent')) {
    function formatAnalysisContent($content) {
        // Convert line breaks to paragraphs
        $content = nl2br($content);

        // Format headers (lines starting with #)
        $content = preg_replace('/^(#+)\s*(.+)$/m', '<h$1>$2</h$1>', $content);

        // Format bold text (**text**)
        $content = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $content);

        // Format italic text (*text*)
        $content = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $content);

        // Format bullet points (lines starting with - or *)
        $content = preg_replace('/^[\-\*]\s*(.+)$/m', '<li>$1</li>', $content);

        // Wrap consecutive list items in ul tags
        $content = preg_replace('/(<li>.*<\/li>(?:\s*<li>.*<\/li>)*)/s', '<ul>$1</ul>', $content);

        // Format numbered lists
        $content = preg_replace('/^\d+\.\s*(.+)$/m', '<li>$1</li>', $content);
        $content = preg_replace('/(<li>.*<\/li>(?:\s*<li>.*<\/li>)*)/s', '<ol>$1</ol>', $content);

        // Format quotes (lines starting with >)
        $content = preg_replace('/^>\s*(.+)$/m', '<blockquote>$1</blockquote>', $content);

        // Wrap paragraphs
        $content = '<div class="analysis-content">' . $content . '</div>';

        return $content;
    }
}
