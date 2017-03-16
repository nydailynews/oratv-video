#!/usr/bin/env python
# -*- coding: utf-8 -*-
# Compare CSVs, adding any new items in the first CSV to the second CSV.
import os
import doctest
import argparse
import unicodecsv
from cStringIO import StringIO

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
