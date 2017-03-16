#!/usr/bin/env python
# -*- coding: utf-8 -*-
# Compare CSVs, adding any new items in the first CSV to the second CSV.
import os
import doctest
import argparse
import csv
import codecs, cStringIO

class UnicodeWriter:
    """ A CSV writer which will write rows to CSV file "f",
        which is encoded in the given encoding.
        """

    def __init__(self, f, dialect=csv.excel, encoding="utf-8", **kwds):
        # Redirect output to a queue
        self.queue = cStringIO.StringIO()
        self.writer = csv.writer(self.queue, dialect=dialect, **kwds)
        self.stream = f
        self.encoder = codecs.getincrementalencoder(encoding)()

    def writerow(self, row):
        self.writer.writerow([s.encode("utf-8") for s in row])
        # Fetch UTF-8 output from the queue ...
        data = self.queue.getvalue()
        data = data.decode("utf-8")
        # ... and reencode it into the target encoding
        data = self.encoder.encode(data)
        # write to the target stream
        self.stream.write(data)
        # empty queue
        self.queue.truncate(0)

    def writerows(self, rows):
        for row in rows:
            self.writerow(row)

def main(args):
    """ For command-line use.
        """
    if args:
        new = 
        articles = []
        for arg in args.fns[0]:
            if args.verbose:
                print arg


def build_parser():
    """ We put the argparse in a method so we can test it
        outside of the command-line.
        """
    parser = argparse.ArgumentParser(usage='$ python addtocsv.py http://domain.com/rss/',
                                     description='''Takes a list of CSVs passed as args.
                                                  Returns the items that are in the first one but not in the subsequent ones.''',
                                     epilog='')
    parser.add_argument("-v", "--verbose", dest="verbose", default=False, action="store_true")
    parser.add_argument("fns", action="append", nargs="*")
    return parser

if __name__ == '__main__':
    """ 
        """
    parser = build_parser()
    args = parser.parse_args()

    if args.verbose:
        doctest.testmod(verbose=args.verbose)

    main(args)
