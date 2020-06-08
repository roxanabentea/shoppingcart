# shoppingcart Engineering Front-end Internship Assessment

Aplicatia ShoppingCart a fost realizata in XAMPP, cele 4 produse le-am introdus intr-o baza de date pentru a interactiona cu ele prin PHP (acesta a fost primul instinct, mai tarziu am observat cat de mult mi-a complicat aplicatia).
Produsele apar initial in ordine descrescatoare dupa pret alaturi de moneda default "USD". Am adaugat un DropDown pentru a selecta una din cele 3 monede uzuale: USD, EUR, GBP. 
Pentru conversie am folosit API-ul http://data.fixer.io/api/latest?access_key=... si le-am prelucrat cu biblioteca money.js.

Odata adaugate in cosul de cumparaturi se poate observa in dreapta sus a numelui un mic chenar gri care dezvaluie descrierea produsului (daca acesta are) atunci cand trecem cu mouse-ul peste el.
In cosul de cumparaturi avem numele produsului + descriere, pretul afisat in moneda aleasa (sau in cea default daca nu), pretul corespunzator pentru cantitatea de produs introdusa si un text clickable pentru a inlatura produsul din cos.

Am incercat sa rezolv si ultimul task pentru modificarea monedei prin URL, in schimb nu am ajuns la niciun rezultat multumitor. Sunt constienta de faptul ca aplicatia are un script destul de alambicat, dar a fost o provocare si pentru mine rezolvarea acestor task-uri. Mi-a facut placere sa ajung la finalul acestei aplicatii si sper ca va fi observat efortul.
