<?php

/**
 * @package   Astroid Framework
 * @author    JoomDev https://www.joomdev.com
 * @copyright Copyright (C) 2009 - 2019 JoomDev.
 * @license https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
 */
defined('_JEXEC') or die;
class AstroidTemplateHelper
{
    public static function slider_font($template) {
        $libraryFonts = AstroidFrameworkHelper::getUploadedFonts($template->template);
        $ast_fontfamily = array();
        // Top Bar Font Styles
        $sliderType = $template->params->get('slider_typography');
        if (trim($sliderType) == 'custom') {
            $slider_font = $template->params->get('slider_typography_options');
            $slider_fontface = str_replace('+', ' ', explode(":", $slider_font->font_face));
            $slider_style = '.tztitle2, .tztitle-slider {';

            $slider_style = ['desktop' => '.tztitle2, .tztitle-slider {', 'tablet' => '.tztitle2, .tztitle-slider {', 'mobile' => '.tztitle2, .tztitle-slider {'];

            if (isset($slider_fontface[0]) && !empty($slider_fontface[0])) {
                if (isset($libraryFonts[$slider_fontface[0]])) {
                    $slider_style['desktop'] .= 'font-family: ' . $libraryFonts[$slider_fontface[0]]['name'] . ',' . $slider_font->alt_font_face . ' !important;';
                    AstroidFrameworkHelper::loadLibraryFont($libraryFonts[$slider_fontface[0]], $template);
                } else {
                    $slider_style['desktop'] .= 'font-family: ' . $slider_fontface[0] . ', ' . $slider_font->alt_font_face . ' !important;';
                    if (!AstroidFrameworkHelper::isSystemFont($slider_fontface[0])) {
                        array_push($ast_fontfamily, $slider_font->font_face);
                    }
                }
            }

            if (isset($slider_font->line_height) && !empty($slider_font->line_height)) {
                if (is_object($slider_font->line_height)) {
                    // if responsive
                    foreach (['desktop', 'tablet', 'mobile'] as $device) {
                        $font_size_unit = isset($slider_font->font_size_unit->{$device}) ? $slider_font->font_size_unit->{$device} : 'em';
                        $slider_style[$device] .= 'font-size: ' . $slider_font->font_size->{$device} . $font_size_unit . ';';
                    }
                } else {
                    // if old type value
                    $font_size_unit = isset($slider_font->font_size_unit) ? $slider_font->font_size_unit : 'em';
                    $slider_style['desktop'] .= 'font-size: ' . $slider_font->font_size . $font_size_unit . ';';
                }
            }

            if (isset($slider_font->font_color) && !empty($slider_font->font_color)) {
                $slider_style['desktop'] .= 'color: ' . $slider_font->font_color . ';';
            }

            if (isset($slider_font->letter_spacing) && !empty($slider_font->letter_spacing)) {
                if (is_object($slider_font->letter_spacing)) {
                    // if responsive
                    foreach (['desktop', 'tablet', 'mobile'] as $device) {
                        $letter_spacing_unit = isset($slider_font->letter_spacing_unit->{$device}) ? $slider_font->letter_spacing_unit->{$device} : 'em';
                        $slider_style[$device] .= 'letter-spacing: ' . $slider_font->letter_spacing->{$device} . $letter_spacing_unit . ';';
                    }
                } else {
                    // if old type value
                    $letter_spacing_unit = isset($slider_font->letter_spacing_unit) ? $slider_font->letter_spacing_unit : 'em';
                    $slider_style['desktop'] .= 'letter-spacing: ' . $slider_font->letter_spacing . $letter_spacing_unit . ';';
                }
            }

            if (isset($slider_font->font_weight) && !empty($slider_font->font_weight)) {
                $slider_style['desktop'] .= 'font-weight: ' . $slider_font->font_weight . ';';
            }

            if (isset($slider_font->line_height) && !empty($slider_font->line_height)) {
                if (is_object($slider_font->line_height)) {
                    // if responsive
                    foreach (['desktop', 'tablet', 'mobile'] as $device) {
                        $line_height_unit = isset($slider_font->line_height_unit->{$device}) ? $slider_font->line_height_unit->{$device} : 'em';
                        $slider_style[$device] .= 'line-height: ' . $slider_font->line_height->{$device} . $line_height_unit . ';';
                    }
                } else {
                    // if old type value
                    $line_height_unit = isset($slider_font->line_height_unit) ? $slider_font->line_height_unit : 'em';
                    $slider_style['desktop'] .= 'line-height: ' . $slider_font->line_height . $line_height_unit . ';';
                }
            }

            if (isset($slider_font->text_transform) && !empty($slider_font->text_transform)) {
                $slider_style['desktop'] .= 'text-transform: ' . $slider_font->text_transform . ';';
            }
            $slider_style['desktop'] .= '}';
            $slider_style['tablet'] .= '}';
            $slider_style['mobile'] .= '}';
            $document = JFactory::getDocument();
            $ast_fontfamily_list = implode("|", str_replace(" ", "+", array_unique($ast_fontfamily)));
            $document->addStyleSheet('https://fonts.googleapis.com/css?family=' . $ast_fontfamily_list);
            // styles for tablet
            $tabletCSS = '';
            if (!empty($styles['tablet'])) {
                $tabletCSS .= $slider_style['tablet'];
            }

// styles for mobile
            $mobileCSS = '';
            if (!empty($styles['mobile'])) {
                $mobileCSS .= $slider_style['mobile'];
            }
            $template->addStyleDeclaration($slider_style['desktop']);
            $template->addStyleDeclaration($tabletCSS, 'tablet');
            $template->addStyleDeclaration($mobileCSS, 'mobile');
        }
    }
}
