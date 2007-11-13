#!/bin/bash

texi2dvi bus-agenda.tex || echo "!! texi2dvi ERROR!";
dvipdf bus-agenda.dvi || echo "!! dvipdf ERROR!";
rm bus-agenda.dvi *~ *aux *log *toc


