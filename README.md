# dlcounter
Download counter for DokuWiki

If you've ever wanted a download counter for DokuWiki to count how many zip, tar, gzip or other downloadable content has been fetched from your media library, this is probably what you want.

Configuring through the admin interface allows you to specify which file extensions to monitor. As data is collected (on a per file basis), you can pull information from the datastore, either one counter at a time, or everything at once displayed in a highly configurable table.

A rich syntax allows you to specify the order of data, whether path information is displayed, left/right justified, whether there is a header and if so, what the header text is. Useful defaults are provided for all options.

#### Syntax
To fetch a counter (just the number) for a specific file:  
`
    {{dlcounter>file?yourFileName.zip}}  
`

To generate a table:  
`    {{dlcounter>name}}`  
or  
`    {{dlcounter>count}}`


The command (name or count) identifies the column you wish to sort on. Since the default sort order is natural, you'll probably want to add a sort option:  
`   {{dlcounter>count?sort}}`  
or  
`    {{dlcounter>count?rsort}}`

There are many other options available. The full documentation can be seen [here](http://philip-p-ide.uk/doku.php/blog:articles:software:doku_dlcounter)
