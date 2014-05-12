#-----------------------------------------------------------------
# Makefile du produit: SYGAFE 1.2
#-----------------------------------------------------------------
#
#  - Standard variables -
#
SRC =  .
INSTALL = sygafe_01.02.tar
#  - Readme and Version -
#
README = ./readme
VERSION = version
LIV = Liv_Sygafe_0102.pdf
#
#
#  - Entry points -
#
all:
	mkdir sygafe
	cp $(SRC)/index.html sygafe
	cp $(SRC)/sygMain.php sygafe
	cp -R $(SRC)/classes sygafe
	cp -R $(SRC)/images sygafe
	cp -R $(SRC)/common sygafe
	cp -R $(SRC)/step1 sygafe
	cp -R $(SRC)/step2 sygafe
	cp ./$(VERSION) sygafe
	cp ./$(README) sygafe
	cp ./$(LIV) sygafe
	
	tar -cvf $(INSTALL) sygafe
	compress $(INSTALL)
	rm -fR sygafe
	mv $(INSTALL).Z livraison/.

clean:
	\rm -f livraison/$(INSTALL).Z
