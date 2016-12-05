using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Text.RegularExpressions;
using System.Data;

namespace Messenger
{
    public partial class index : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            txtBDay.Attributes.Add("placeholder", "mm/dd/yyyy");
            btnLogin.Attributes.Add("disabled", "ture");
        }

        protected void btnLogin_Click(object sender, EventArgs e)
        {
            Regex rgx = new Regex(@"^\d{2}\/\d{2}\/\d{4}$");

            if((rgx.IsMatch(txtBDay.Text.Trim()) == true) && (txtFName.Text.Trim() != "") && (txtLName.Text.Trim() != ""))
            {
                DataView datView = (DataView) loginPerson.Select(DataSourceSelectArguments.Empty);
                if (datView.Count == 0)
                {
                    loginPerson.Insert();
                    Session["fName"] = txtFName.Text;
                    Session["lName"] = txtLName.Text;
                    Session["bDay"] = txtBDay.Text;
                } else
                {
                    foreach (DataRowView drvSql in datView)
                    {
                        Session["fName"] = txtFName.Text;
                        Session["lName"] = txtLName.Text;
                        Session["bDay"] = txtBDay.Text;
                    }
                }

                Response.Redirect("pages/groups.aspx", false);
            } else
            {
                txtFName.Text = "";
                txtLName.Text = "";
                txtBDay.Text = "";
            }
        }
    }
}