<?xml version="1.0"?>
<package xmlns="http://www.idpf.org/2007/opf" unique-identifier="BookID" version="2.0">
    <metadata xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:opf="http://www.idpf.org/2007/opf">
        <dc:title>{$title}</dc:title>
        <dc:creator opf:role="aut">{$author}</dc:creator>
        <dc:date opf:event="creation">{$date}</dc:date>
{if $publisher!=""}
        <dc:publisher>{$publisher}</dc:publisher>
{/if}
        <dc:description>{$description}</dc:description>
        <dc:language>ja</dc:language>
        <dc:identifier id="BookID" opf:scheme="URL">{$uid}</dc:identifier>
{*        <meta name="{$smarty.const.app_name}" content="0.0.1"/>*}
        <meta name="cover" content="image-cover-image"/>
    </metadata>
    <manifest>
{foreach from=$item item=d}
        <item id="{$d.id}" href="{$d.href}" media-type="{$d.mediatype}"/>
{/foreach}
    </manifest>
    <spine toc="ncx">
{foreach from=$itemref item=d}
        <itemref idref="{$d.idref}"{if $d.linear!=""} linear="{$d.linear}"{/if}/>
{/foreach}
    </spine>
</package>
