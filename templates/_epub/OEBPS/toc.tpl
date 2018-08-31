<?xml version="1.0"?>
<!DOCTYPE ncx PUBLIC "-//NISO//DTD ncx 2005-1//EN" "http://www.daisy.org/z3986/2005/ncx-2005-1.dtd">
<ncx xmlns="http://www.daisy.org/z3986/2005/ncx/" version="2005-1">
    <head>
        <meta name="dtb:uid" content="{$uid}"/>
        <meta name="dtb:depth" content="1"/>
        <meta name="dtb:totalPageCount" content="0"/>
        <meta name="dtb:maxPageNumber" content="0"/>
    </head>
    <docTitle>
        <text>{$title}</text>
    </docTitle>
    <navMap>
{foreach from=$navPoint item=d name=nav_point}
        <navPoint id="navPoint-{$smarty.foreach.nav_point.iteration}" playOrder="{$smarty.foreach.nav_point.iteration}">
            <navLabel>
                <text>{$d.text}</text>
            </navLabel>
            <content src="{$d.src}" />
        </navPoint>
{/foreach}
    </navMap>
</ncx>
