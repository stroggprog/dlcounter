# dlcounter
Download counter for DokuWiki
**NOTE: I wrote dlounter but lost control of the phil-ide account where the original repository was stored, so I've cloned it here. This is now the official repo for _dlcounter_ and all future updates (if any) will be found here**

If you've ever wanted a download counter for DokuWiki to count how many zip, tar, gzip or other downloadable content has been fetched from your media library, this is probably what you want.

Configuring through the admin interface allows you to specify which file extensions to monitor. As data is collected (on a per file basis), you can pull information from the datastore, either one counter at a time, or everything at once displayed in a highly configurable table.

A rich syntax allows you to specify the order of data, whether path information is displayed, left/right justified, whether there is a header and if so, what the header text is. Useful defaults are provided for all options.

A description of the datastore is also provided with some example code on how to access it and retrieve the data, so you can perform any other operations on the data or inject the data into your own (more complex) tables.

#### Syntax
To fetch a counter (just the number) for a specific file:  
```wiki
    {{dlcounter>file?yourFileName.zip}}  
```

To generate a table:  
```wiki
    {{dlcounter>name}}  
    or  
    {{dlcounter>count}}  
````

The command (*name* or *count*) identifies the column you wish to sort on. Since the default sort order is natural, you'll probably want to add a sort option:  
```wiki
    {{dlcounter>count?sort}}  
    or  
    {{dlcounter>count?rsort}}  
```
A complete list of optional parameters:
```wiki
  SORTING  
  sort  => orders the data in ascending order  
  rsort => orders the data in descending order  
   
  FILENAME DISPLAY  
  left   => left-align the filenames  
  center => center the names  
  right  => right-align the filenames  
   
  strip => strip the path information to leave just the filename  
  nobold => do not highlight the filename  
   
  COUNTER DISPLAY  
  minwidth => minimum width of the column  
  cpad     => left-pad the column with spaces  
    
  HEADER DISPLAY  
  hleft   => left-align the header  
  hcenter => center the header  
  hright  => right-align the header  
    
  noheader => do not display a header  
  htext    => set alternate text for header  
```
Tables display the full path of the files as they appear in the media library (e.g. path:to:snow.zip) unless the *strip* option is used. Sorting on filenames is sorted on the filename with the path as a subsort.

The full documentation can be seen [here](http://philip-p-ide.uk/doku.php/blog:articles:software:doku_dlcounter)
