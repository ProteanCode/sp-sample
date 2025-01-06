<?php

namespace App\Enums;

enum IPTC: string
{
    case IPTC_OBJECT_NAME = '005';
    case IPTC_EDIT_STATUS = '007';
    case IPTC_PRIORITY = '010';
    case IPTC_CATEGORY = '015';
    case IPTC_SUPPLEMENTAL_CATEGORY = '020';
    case IPTC_FIXTURE_IDENTIFIER = '022';
    case IPTC_KEYWORDS = '025';
    case IPTC_RELEASE_DATE = '030';
    case IPTC_RELEASE_TIME = '035';
    case IPTC_SPECIAL_INSTRUCTIONS = '040';
    case IPTC_REFERENCE_SERVICE = '045';
    case IPTC_REFERENCE_DATE = '047';
    case IPTC_REFERENCE_NUMBER = '050';
    case IPTC_CREATED_DATE = '055';
    case IPTC_CREATED_TIME = '060';
    case IPTC_ORIGINATING_PROGRAM = '065';
    case IPTC_PROGRAM_VERSION = '070';
    case IPTC_OBJECT_CYCLE = '075';
    case IPTC_BYLINE = '080';
    case IPTC_BYLINE_TITLE = '085';
    case IPTC_CITY = '090';
    case IPTC_PROVINCE_STATE = '095';
    case IPTC_COUNTRY_CODE = '100';
    case IPTC_COUNTRY = '101';
    case IPTC_ORIGINAL_TRANSMISSION_REFERENCE = '103';
    case IPTC_HEADLINE = '105';
    case IPTC_CREDIT = '110';
    case IPTC_SOURCE = '115';
    case IPTC_COPYRIGHT_STRING = '116';
    case IPTC_CAPTION = '120';
    case IPTC_LOCAL_CAPTION = '121';
}
