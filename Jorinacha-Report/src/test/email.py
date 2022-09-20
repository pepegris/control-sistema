from multiprocessing import connection
import pyodbc

from email.message import EmailMessage
import ssl
import smtplib

try:
    connection=pyodbc.connect('DRIVER={SQL Server};SERVER=172.16.1.19;DATABASE=previa_a ;UID=mezcla;PWD=Zeus33$')
    print ("conexion exitosa \n")

    cursor=connection.cursor()
    cursor.execute("select TOP 1 tipo_doc,nro_doc,anulado,co_cli,observa  from docum_cc order by fe_us_in desc")
    rows=cursor.fetchall()
    inf = {}
    
    for row in rows:
        tipo_doc=row[0]
        nro_doc=row[1]
        observa=row[4]

    email_emisor = 'soporte.it.jorinacha@gmail.com'
    email_pass ='rcnkchteocmgcaoe'
    email_receptor ='salcedop816@hotmail.com'


    asunto = 'PRObANDO CORREOS'


    cuerpo= f"""
    {email_receptor}
    este es el correo que se va enviar \n
    {tipo_doc}
    {nro_doc}
    {observa}
    prueba de envio
    """

    em=EmailMessage()
    em['From'] = email_emisor
    em['To'] = email_receptor
    em['Subject'] = asunto
    em.set_content(cuerpo)


    contexto =  ssl.create_default_context()

    with smtplib.SMTP_SSL('smtp.gmail.com' ,465, context = contexto ) as smtp :
        smtp.login(email_emisor , email_pass)
        smtp.sendmail(email_emisor,email_receptor,em.as_string())
   
        
except Exception as ex:
    print(ex)

