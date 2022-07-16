# stupid-simple-image-optimization
A simple image optimization script written in PHP and GD; perfect for automation!

    Version: 1.0
    Author: Will Leffert (wfl@classicwfl.com)
    Contributors: A bit of Stack Overflow and a lot of Yoo-Hoo.
    License: MIT. Do whatever you like.
    Requirements: A recent PHP (5/7) & GD; Did testing on 7.1 for giggles. Really not
        much to worry about, but some versions of 5.x don't play well with GD, FYI.
    Purpose: A clean, stripped down script that can be used to compress images on call.
        I've been dealing with inventory systems that have uncompressed images in their
        CSV; good dimensions, but uncompressed. Not good for load times or SEO. So, I
        decided to create this and share it, as I've never actually posted anything to
        GitHub before, and figured.. Let's start now.
    Future Plans: If I ever get bored and decide to add new features, I'll probably add
        in image sizing. Possibly additional file format support, too, and a format
        switcher. Image sizing is obviously a good feature, but.. I HATE doing image
        scaling with GD/PHP, and after trying to figure out why it didn't work during
        the intial dev, I dropped it; no sense in putting it in currently, as it isn't
        needed for the original purpose of the script.
    Usage: Stupid-simple. Just change the defines right below here to meet your needs.
        I'd recommend storing it in a non-public folder (.htaccess is your friend) and
        scheduling it with CRON, otherwise someone could just hammer this script and
        waste cycles.. Which, honestly, shouldn't be much of an issue unless you've got
        a REALLY slow server. This is a clean, fast way to optimize images without
        bloat.
